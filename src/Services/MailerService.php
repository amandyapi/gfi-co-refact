<?php

namespace App\Services;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use App\Helpers\TypeHelper;
use App\Exception\ExceptionApi;

use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;

use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Builder\Builder;
use Symfony\Component\Mime\Part\DataPart;
use Dompdf\Options;
use Dompdf\Dompdf;
use Twig\Environment;
use App\Utils\Constants;

class MailerService
{
    public function __construct(
        private MailerInterface $mailer,
        private ParameterBagInterface $parameterBag,
        private Environment $twig
    ) {}


    public function send(object $data, $debug = true)
    {
        $this->checkRequirements($data);

        try {
            $email = null;
            switch ($data->type) {
                case 'text':
                    $email = (new Email());
                    $email = $this->addData($email, $data);
                    $email
                        ->text($data->content);
                    break;
                case 'html':
                    $email = (new Email());
                    $email = $this->addData($email, $data);
                    $email
                        ->html($data->content);
                    break;
                case 'template':
                    $email = (new TemplatedEmail());
                    $email = $this->addData($email, $data);
                    $email
                        ->htmlTemplate($data->template)
                        ->context($data->context);
                    break;

                default:
                    throw new ExceptionApi('Type de mail non pris en charge (1).');
                    break;
            }

            // Ajout du fichier en pièce jointe
            if ($this->checkAttachementsRequirements($data)) {
                $pdfAttachment = $this->createAttachment($data);
                
                $email
                    ->addPart($pdfAttachment)
                ;
            }

            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            //dd($e);
        }
    }

    public function addData($email, object $data)
    {
        $email
            ->from((isset($data->name) && !TypeHelper::is_not_null($data->name)) ? new Address($data->from, $data->name) : new Address($data->from))
            //->priority((isset($data->priority) && defined('Email::'.$data->priority))?$data->priority:Email::PRIORITY_NORMAL) 
            ->subject($data->subject)
        ;
        // To
        if (\is_array($data->to) && TypeHelper::is_not_null($data->to)) {
            $email->to(...$data->to);
        } else if (!\is_array($data->to) && TypeHelper::is_not_null($data->to)) {
            $email
                ->to($data->to);
        }

        // Cc
        if (isset($data->cc) && \is_array($data->cc) && TypeHelper::is_not_null($data->cc)) {
            $email
                ->cc(...$data->cc);
        } else if (isset($data->cc) && !\is_array($data->cc) && TypeHelper::is_not_null($data->cc)) {
            $email
                ->cc($data->cc);
        }

        // ReplyTo
        if (isset($data->replyTo) && TypeHelper::is_not_null($data->replyTo)) $email->replyTo($data->replyTo);

        return $email;
    }

    private function createAttachment(object $data): DataPart
    {
        $mimeType = $data->attachment['mime'];
        $errorMessage = "Unsupported MIME type: {$mimeType}";
        
        if(!$this->isValidMimeType($mimeType)) throw new ExceptionApi($errorMessage);

        switch ($mimeType) {
            case 'PDF':
                return $this->createPdfAttachment($data);
            case 'CSV':
                return $this->createCsvAttachment($data);
            default:
                throw new ExceptionApi($errorMessage);
        }
    }

    private function createPdfAttachment(object $data): DataPart
    {
        $qrCode = null;
        $qrCodeSrc = null;
        $qrCodePath = null;
        $twigData = [];

        if (isset($data->attachment['token']) && !empty($data->attachment['token'])) {
            $qrCode = $this->createQrCode($data->attachment['token']);
            $qrCodeSrc = $qrCode['src'];
            $qrCodePath = $qrCode['path'];
            $twigData['qrCode'] = $qrCodeSrc;
        }

        $options = new Options();
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);

        // Préparation des données pour Twig
        $twigData['title'] = $data->attachment['title'];
        $twigData['headers'] = $data->attachment['headers'];
        $twigData['rows'] = $data->attachment['rows'];
        $twigData['token'] = $data->attachment['token'];

        $html = $this->twig->render($data->attachment['template'], $twigData);

        $size = $data->attachment['size'] ?? 'A4';
        $orientation = $data->attachment['orientation'] ?? 'portrait';
        
        $dompdf
            ->setPaper($size, $orientation)
            ->loadHtml($html)
        ;
        $dompdf->render();
        $pdfOutput = $dompdf->output();

        if (isset($qrCodePath)) unlink($qrCodePath);
        return new DataPart($pdfOutput, $data->attachment['fileName'].'.pdf', Constants::MIME_TYPE[$data->attachment['mime']]);
    }


    private function createCsvAttachment(object $data): DataPart
    {
        $csvContent = '';

        // Générer l'entête CSV
        if (isset($data->attachment['headers'])) {
            $csvContent .= implode(',', $data->attachment['headers']) . "\n";
        }

        // Ajouter les lignes de données
        foreach ($data->attachment['rows'] as $row) {
            $csvContent .= implode(',', $row) . "\n";
        }

        // Créer et retourner l'objet DataPart pour la pièce jointe CSV
        return new DataPart($csvContent, $data->attachment['fileName'].'.csv', Constants::MIME_TYPE[$data->attachment['mime']]);
    }

    private function createQrCode(string $qrCodeToken)
    {
        // Générer le QR code à partir du token
        $qrCodeResult = Builder::create()
            ->writer(new PngWriter())
            ->data($qrCodeToken) // Le token est passé dans $data
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(100)
            ->margin(10)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->build();

        // Sauvegarder le QR code dans un fichier temporaire
        $qrCodePath = tempnam(sys_get_temp_dir(), 'qrcode') . '.png';
        \file_put_contents($qrCodePath, $qrCodeResult->getString());

        // Convertir le QR code en base64 pour l'intégrer dans le PDF via Twig
        $qrCodeBase64 = base64_encode(file_get_contents($qrCodePath));
        $qrCodeSrc = 'data:image/png;base64,' . $qrCodeBase64;

        return ['src' => $qrCodeSrc, 'path' => $qrCodePath];
    }

    public function checkRequirements(object $data)
    {
        if (!isset($data->from) || !TypeHelper::is_not_null($data->from)) throw new ExceptionApi("Emetteur non défini");
        if (!isset($data->to) || !TypeHelper::is_not_null($data->to)) throw new ExceptionApi("Destinataire non défini");
        if (!isset($data->subject) || !TypeHelper::is_not_null($data->subject)) throw new ExceptionApi("Objet non défini");
        if (!isset($data->type) || !TypeHelper::is_not_null($data->type)) throw new ExceptionApi("Type non défini");

        if ($data->type !== 'template' && (!isset($data->content) || !TypeHelper::is_not_null($data->content))) throw new ExceptionApi("Contenu non défini");
        if ($data->type == 'template' && (!isset($data->template) || !TypeHelper::is_not_null($data->template))) throw new ExceptionApi("Template non défini");
        if ($data->type == 'template' && (!isset($data->context) || !TypeHelper::is_not_null($data->context))) throw new ExceptionApi("Context non défini");

        if (isset($data->context) && !\is_array($data->context)) throw new ExceptionApi("Le context doit être un tableau");
    }

    public function checkAttachementsRequirements(object $data)
    {
        return (
            isset($data->attachment) && \is_array($data->attachment) && TypeHelper::is_not_null($data->attachment) &&
            isset($data->attachment['title']) && TypeHelper::is_not_null($data->attachment['title']) &&
            isset($data->attachment['template']) && TypeHelper::is_not_null($data->attachment['template']) &&
            isset($data->attachment['mime']) && TypeHelper::is_not_null($data->attachment['mime']) && $this->isValidMimeType($data->attachment['mime']) &&
            isset($data->attachment['headers']) && \is_array($data->attachment['headers']) &&
            isset($data->attachment['rows']) && \is_array($data->attachment['rows'])
        );
    }

    public function isValidMimeType(string $mimeType): bool
    {
        // Vérifier si le type MIME est dans la liste des types MIME valides
        return \array_key_exists(\strtoupper($mimeType), Constants::MIME_TYPE);
    }
}
