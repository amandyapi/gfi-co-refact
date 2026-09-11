<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class QuoteService
{
    public function __construct(
        private MailerInterface $mailer,
        private LoggerInterface $logger,
        private string $adminEmail = 'contact@gfi-co.net',
        private string $senderEmail = 'noreply@gfi-co.net'
    ) {
    }

    /**
     * Traite la demande de devis et envoie les emails.
     *
     * @param array $data Les données du formulaire (fullName, phone, projectType, landSize, budget, finishLevel, message)
     * @param string $locale La langue courante (fr|en)
     * @return bool True si l'envoi a réussi, false sinon
     */
    public function processQuoteRequest(array $data, string $locale = 'fr'): bool
    {
        // Validation des données requises
        $required = ['fullName', 'phone', 'projectType', 'landSize', 'budget', 'finishLevel'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $this->logger->warning('Quote form: missing required field', [
                    'field' => $field,
                    'data' => $data,
                ]);
                return false;
            }
        }

        try {
            // 1. Notification à l'administrateur
            $this->sendAdminNotification($data, $locale);

            // 2. Confirmation au demandeur
            $this->sendUserConfirmation($data, $locale);

            return true;

        } catch (TransportExceptionInterface $e) {
            $this->logger->error('Quote form: mail transport error', [
                'message' => $e->getMessage(),
                'data' => $data,
            ]);
            return false;

        } catch (\Exception $e) {
            $this->logger->error('Quote form: unexpected error', [
                'message' => $e->getMessage(),
                'data' => $data,
            ]);
            return false;
        }
    }

    /**
     * Envoie l'email de notification à l'administrateur
     */
    private function sendAdminNotification(array $data, string $locale): void
    {
        $subject = $locale === 'en'
            ? sprintf('[Quote Request] New request from %s', $data['fullName'])
            : sprintf('[Demande de devis] Nouvelle demande de %s', $data['fullName']);

        $email = (new TemplatedEmail())
            ->from(new Address($this->senderEmail, 'GFI-CO Website'))
            ->to(new Address($this->adminEmail, 'GFI-CO GROUP'))
            ->subject($subject)
            ->htmlTemplate('emails/quote-notification.html.twig')
            ->context([
                'data' => $data,
                'locale' => $locale,
            ]);

        $this->mailer->send($email);
    }

    /**
     * Envoie l'email de confirmation à l'utilisateur
     *
     * NOTE : L'utilisateur n'a pas fourni d'email dans ce formulaire,
     * donc on ne peut pas lui envoyer de confirmation.
     * On envoie la confirmation uniquement à l'admin.
     */
    private function sendUserConfirmation(array $data, string $locale): void
    {
        // Pas d'email utilisateur dans ce formulaire,
        // donc pas d'envoi de confirmation possible.
        // Cette méthode est laissée vide pour permettre une extension future.
    }
}