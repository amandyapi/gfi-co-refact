<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class ContactService
{
    public function __construct(
        private MailerInterface $mailer,
        private LoggerInterface $logger,
        private string $adminEmail = 'contact@gfi-co.net',
        private string $senderEmail = 'noreply@gfi-co.net'
    ) {
    }

    /**
     * Traite la soumission du formulaire de contact et envoie les emails.
     *
     * @param array $data Les données du formulaire (firstname, lastname, email, phone, message)
     * @param string $locale La langue courante (fr|en)
     * @return bool True si l'envoi a réussi, false sinon
     */
    public function processContactForm(array $data, string $locale = 'fr'): bool
    {
        // Validation basique des données requises
        if (empty($data['firstname']) || empty($data['lastname']) 
            || empty($data['email']) || empty($data['message'])) {
            $this->logger->warning('Contact form: missing required fields', ['data' => $data]);
            return false;
        }

        // Validation de l'email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->logger->warning('Contact form: invalid email', ['email' => $data['email']]);
            return false;
        }

        try {
            // 1. Envoi de l'email de notification à l'administrateur
            $this->sendAdminNotification($data, $locale);

            // 2. Envoi de l'email de confirmation à l'utilisateur
            $this->sendUserConfirmation($data, $locale);

            return true;

        } catch (TransportExceptionInterface $e) {
            $this->logger->error('Contact form: mail transport error', [
                'message' => $e->getMessage(),
                'data' => $data,
            ]);
            return false;

        } catch (\Exception $e) {
            $this->logger->error('Contact form: unexpected error', [
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
            ? sprintf('[Contact] New message from %s %s', $data['firstname'], $data['lastname'])
            : sprintf('[Contact] Nouveau message de %s %s', $data['firstname'], $data['lastname']);

        $email = (new TemplatedEmail())
            ->from(new Address($this->senderEmail, 'GFI-CO Website'))
            ->to(new Address($this->adminEmail, 'GFI-CO GROUP'))
            ->replyTo(new Address($data['email'], $data['firstname'] . ' ' . $data['lastname']))
            ->subject($subject)
            ->htmlTemplate('emails/contact-notification.html.twig')
            ->context([
                'data' => $data,
                'locale' => $locale,
            ]);

        $this->mailer->send($email);
    }

    /**
     * Envoie l'email de confirmation à l'utilisateur
     */
    private function sendUserConfirmation(array $data, string $locale): void
    {
        $subject = $locale === 'en'
            ? 'We received your message - GFI-CO GROUP'
            : 'Nous avons bien reçu votre message - GFI-CO GROUP';

        $email = (new TemplatedEmail())
            ->from(new Address($this->senderEmail, 'GFI-CO GROUP'))
            ->to(new Address($data['email'], $data['firstname'] . ' ' . $data['lastname']))
            ->subject($subject)
            ->htmlTemplate('emails/contact-confirmation.html.twig')
            ->context([
                'data' => $data,
                'locale' => $locale,
            ]);

        $this->mailer->send($email);
    }
}