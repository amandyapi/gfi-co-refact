<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('pages/home.html.twig');
    }

    #[Route('/a-propos', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('pages/about.html.twig', [
            'bread_subtitle' => 'Qui sommes-nous ?',
            'bread_title' => 'À propos de GFI-CO GROUP',
        ]);
    }

    #[Route('/nos-solutions', name: 'app_solutions')]
    public function solutions(): Response
    {
        return $this->render('pages/solutions.html.twig', [
            'bread_subtitle' => 'Nos prestations',
            'bread_title' => 'Nos solutions',
        ]);
    }

    #[Route('/actualites', name: 'app_blog')]
    public function blog(): Response
    {
        return $this->render('pages/blog/blog.html.twig', [
            'bread_subtitle' => 'Nos actualités',
            'bread_title' => 'Blog & actualités',
        ]);
    }

    #[Route('/actualites/{slug}', name: 'app_blog_single')]
    public function blogSingle(string $slug): Response
    {
        // Récupérer l'article via son slug
        // $article = $this->blogRepository->findOneBySlug($slug);
        
        return $this->render('pages/blog/blog-single.html.twig', [
            'bread_subtitle' => 'Article',
            'bread_title' => 'Lire l\'article',
            'slug' => $slug,
            // 'article' => $article,
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('pages/contact.html.twig', [
            'bread_subtitle' => 'Nous contacter',
            'bread_title' => 'Contactez-nous',
        ]);
    }

    #[Route('/mentions-legales', name: 'app_legal')]
    public function legal(): Response
    {
        return $this->render('pages/legal.html.twig', [
            'bread_subtitle' => 'Informations légales',
            'bread_title' => 'Mentions légales',
        ]);
    }

    #[Route('/politique-de-confidentialite', name: 'app_privacy')]
    public function privacy(): Response
    {
        return $this->render('pages/privacy.html.twig', [
            'bread_subtitle' => 'Protection des données',
            'bread_title' => 'Politique de confidentialité',
        ]);
    }

    #[Route('/recherche', name: 'app_search')]
    public function search(): Response
    {
        return $this->render('pages/search.html.twig', [
            'bread_subtitle' => 'Recherche',
            'bread_title' => 'Résultats de recherche',
        ]);
    }

    #[Route('/devis', name: 'app_quote_submit')]
    public function devis(): Response
    {
        return $this->render('pages/search.html.twig', [
            'bread_subtitle' => 'Recherche',
            'bread_title' => 'Résultats de recherche',
        ]);
    }
}