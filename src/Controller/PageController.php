<?php

namespace App\Controller;

use App\Service\ContactService;
use App\Service\QuoteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    public function __construct(
        public ContactService $contactService,
        public QuoteService $quoteService
    ){}

    #[Route('/{_locale}/home', name: 'app_home', requirements: ['_locale' => 'fr|en'])]
    public function index(string $_locale = 'fr'): Response
    {
        return $this->render('pages/home-' . $_locale . '.html.twig');
    }

    #[Route('/{_locale}/a-propos', name: 'app_about', requirements: ['_locale' => 'fr|en'])]
    public function about(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Who are we?',
            'bread_title' => 'About GFI-CO GROUP',
        ] : [
            'bread_subtitle' => 'Qui sommes-nous ?',
            'bread_title' => 'À propos de GFI-CO GROUP',
        ];
        
        return $this->render('pages/about-' . $_locale . '.html.twig', $data);
    }

    #[Route('/{_locale}/nos-solutions', name: 'app_solutions', requirements: ['_locale' => 'fr|en'])]
    public function solutions(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Our services',
            'bread_title' => 'Our solutions',
        ] : [
            'bread_subtitle' => 'Nos prestations',
            'bread_title' => 'Nos solutions',
        ];
        
        return $this->render('pages/solutions-' . $_locale . '.html.twig', $data);
    }

    #[Route('/{_locale}/contact', name: 'app_contact', requirements: ['_locale' => 'fr|en'])]
    public function contact(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Contact us',
            'bread_title' => 'Contact us',
        ] : [
            'bread_subtitle' => 'Nous contacter',
            'bread_title' => 'Contactez-nous',
        ];
        
        return $this->render('pages/contact-' . $_locale . '.html.twig', $data);
    }

    #[Route('/{_locale}/contact-submit', name: 'app_contact_submit', methods: ['POST'], requirements: ['_locale' => 'fr|en'])]
    public function contactSubmit(Request $request, string $_locale = 'fr'): Response
    {
        // Récupération des données du formulaire
        $data = [
            'firstname' => trim($request->request->get('firstname', '')),
            'lastname'  => trim($request->request->get('lastname', '')),
            'email'     => trim($request->request->get('email', '')),
            'phone'     => trim($request->request->get('phone', '')),
            'message'   => trim($request->request->get('message', '')),
        ];

        // Traitement via le service
        $success = $this->contactService->processContactForm($data, $_locale);

        if ($success) {
            $message = $_locale === 'en'
                ? 'Your message has been sent successfully! We will contact you within 48 hours.'
                : 'Votre message a été envoyé avec succès ! Nous vous contacterons sous 48h.';

            $this->addFlash('success', $message);
        } else {
            $message = $_locale === 'en'
                ? 'An error occurred while sending your message. Please try again.'
                : 'Une erreur est survenue lors de l\'envoi de votre message. Veuillez réessayer.';

            $this->addFlash('error', $message);
        }

        return $this->redirectToRoute('app_contact', ['_locale' => $_locale]);
    }

    #[Route('/{_locale}/mentions-legales', name: 'app_legal', requirements: ['_locale' => 'fr|en'])]
    public function legal(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Legal information',
            'bread_title' => 'Legal notice',
        ] : [
            'bread_subtitle' => 'Informations légales',
            'bread_title' => 'Mentions légales',
        ];
        
        return $this->render('pages/legal-' . $_locale . '.html.twig', $data);
    }

    #[Route('/{_locale}/politique-de-confidentialite', name: 'app_privacy', requirements: ['_locale' => 'fr|en'])]
    public function privacy(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Data protection',
            'bread_title' => 'Privacy policy',
        ] : [
            'bread_subtitle' => 'Protection des données',
            'bread_title' => 'Politique de confidentialité',
        ];
        
        return $this->render('pages/privacy-' . $_locale . '.html.twig', $data);
    }

    #[Route('/{_locale}/recherche', name: 'app_search', requirements: ['_locale' => 'fr|en'])]
    public function search(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Search',
            'bread_title' => 'Search results',
        ] : [
            'bread_subtitle' => 'Recherche',
            'bread_title' => 'Résultats de recherche',
        ];
        
        return $this->render('pages/search-' . $_locale . '.html.twig', $data);
    }

    #[Route('/devis', name: 'app_quote_submit', methods: ['POST'])]
    public function devis(Request $request): JsonResponse
    {
        // Récupération des données JSON envoyées par fetch
        $content = json_decode($request->getContent(), true) ?? [];

        // On détermine la locale depuis les données ou l'URL
        // (la route n'a pas _locale, on peut la passer dans les données)
        $locale = $content['locale'] ?? $request->getLocale() ?? 'fr';

        // Validation basique
        if (empty($content['fullName']) || empty($content['phone'])) {
            return $this->json([
                'success' => false,
                'message' => $locale === 'en' 
                    ? 'Please fill in the required fields.' 
                    : 'Veuillez remplir les champs obligatoires.',
            ], 400);
        }

        // Traitement via le service
        $success = $this->quoteService->processQuoteRequest($content, $locale);

        if ($success) {
            return $this->json([
                'success' => true,
                'message' => $locale === 'en'
                    ? 'Your quote request has been sent successfully!'
                    : 'Votre demande de devis a été envoyée avec succès !',
            ]);
        }

        return $this->json([
            'success' => false,
            'message' => $locale === 'en'
                ? 'An error occurred while sending your request. Please try again.'
                : 'Une erreur est survenue lors de l\'envoi de votre demande. Veuillez réessayer.',
        ], 500);
    }

    // Route de redirection pour la racine (redirige vers FR par défaut)
    #[Route('/', name: 'app_home_redirect')]
    public function homeRedirect(): Response
    {
        return $this->redirectToRoute('app_home', ['_locale' => 'fr']);
    }

    #[Route('/{_locale}/404', name: 'app_404', requirements: ['_locale' => 'fr|en'])]
    public function error404(string $_locale = 'fr'): Response
    {
        $template = 'pages/404-' . $_locale . '.html.twig';
        
        return $this->render($template);
    }   
}