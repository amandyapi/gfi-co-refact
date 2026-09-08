<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/branches', name: 'app_branches_')]
class BrancheController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('pages/branches/branches.html.twig', [
            'bread_subtitle' => 'Nos activités',
            'bread_title' => 'Nos branches',
        ]);
    }

    #[Route('/promotion-immobiliere', name: 'promotion')]
    public function promotion(): Response
    {
        return $this->render('pages/branches/branche-promotion.html.twig', [
            'bread_subtitle' => 'Développeur immobilier',
            'bread_title' => 'GFI-CO Développeur immobilier',
            'branche' => 'promotion',
        ]);
    }

    #[Route('/construction', name: 'construction')]
    public function construction(): Response
    {
        return $this->render('pages/branches/branche-construction.html.twig', [
            'bread_subtitle' => 'Génie civil & construction',
            'bread_title' => 'GFI-CO Construction',
            'branche' => 'construction',
        ]);
    }

    #[Route('/design-architecture', name: 'design')]
    public function design(): Response
    {
        return $this->render('pages/branches/branche-design.html.twig', [
            'bread_subtitle' => 'Création & aménagement',
            'bread_title' => 'GFI-CO Design & Architecture',
            'branche' => 'design',
        ]);
    }

    #[Route('/invest', name: 'invest')]
    public function invest(): Response
    {
        return $this->render('pages/branches/branche-invest.html.twig', [
            'bread_subtitle' => 'Investissement immobilier',
            'bread_title' => 'GFI-CO Invest',
            'branche' => 'invest',
        ]);
    }

    #[Route('/agence-immobiliere', name: 'agency')]
    public function agency(): Response
    {
        return $this->render('pages/branches/branche-agency.html.twig', [
            'bread_subtitle' => 'Intermédiation commerciale',
            'bread_title' => 'GFI-CO Agency',
            'branche' => 'agency',
        ]);
    }
}