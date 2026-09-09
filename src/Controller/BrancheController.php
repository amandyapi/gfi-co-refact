<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale}/branches', name: 'app_branches_', requirements: ['_locale' => 'fr|en'])]
class BrancheController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Our activities',
            'bread_title' => 'Our branches',
        ] : [
            'bread_subtitle' => 'Nos activités',
            'bread_title' => 'Nos branches',
        ];
        
        return $this->render('pages/branches/branches-' . $_locale . '.html.twig', $data);
    }

    #[Route('/developpeur-immobilier', name: 'promotion')]
    public function promotion(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Real estate developer',
            'bread_title' => 'GFI-CO Real Estate Developer',
            'branche' => 'promotion',
        ] : [
            'bread_subtitle' => 'Développeur immobilier',
            'bread_title' => 'GFI-CO Développeur immobilier',
            'branche' => 'promotion',
        ];
        
        return $this->render('pages/branches/promotion-' . $_locale . '.html.twig', $data);
    }

    #[Route('/construction', name: 'construction')]
    public function construction(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Civil engineering & construction',
            'bread_title' => 'GFI-CO Construction',
            'branche' => 'construction',
        ] : [
            'bread_subtitle' => 'Génie civil & construction',
            'bread_title' => 'GFI-CO Construction',
            'branche' => 'construction',
        ];
        
        return $this->render('pages/branches/construction-' . $_locale . '.html.twig', $data);
    }

    #[Route('/design-architecture', name: 'design')]
    public function design(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Design & architecture',
            'bread_title' => 'GFI-CO Design & Architecture',
            'branche' => 'design',
        ] : [
            'bread_subtitle' => 'Création & aménagement',
            'bread_title' => 'GFI-CO Design & Architecture',
            'branche' => 'design',
        ];
        
        return $this->render('pages/branches/design-' . $_locale . '.html.twig', $data);
    }

    #[Route('/invest', name: 'invest')]
    public function invest(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Real estate investment',
            'bread_title' => 'GFI-CO Invest',
            'branche' => 'invest',
        ] : [
            'bread_subtitle' => 'Investissement immobilier',
            'bread_title' => 'GFI-CO Invest',
            'branche' => 'invest',
        ];
        
        return $this->render('pages/branches/invest-' . $_locale . '.html.twig', $data);
    }

    #[Route('/agence-immobiliere', name: 'agency')]
    public function agency(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Real estate agency',
            'bread_title' => 'GFI-CO Agency',
            'branche' => 'agency',
        ] : [
            'bread_subtitle' => 'Intermédiation commerciale',
            'bread_title' => 'GFI-CO Agency',
            'branche' => 'agency',
        ];
        
        return $this->render('pages/branches/agency-' . $_locale . '.html.twig', $data);
    }
}