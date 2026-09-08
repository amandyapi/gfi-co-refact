<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/projets', name: 'app_projets_')]
class ProjetController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('pages/projets/index.html.twig', [
            'bread_subtitle' => 'Nos réalisations',
            'bread_title' => 'Tous nos projets',
        ]);
    }

    // ============================================
    // GROUPE 1 : CONCEPTION
    // ============================================
    #[Route('/conception', name: 'conception')]
    public function conception(): Response
    {
        return $this->render('pages/projets/conception/index.html.twig', [
            'bread_subtitle' => 'Conception plans et projets',
            'bread_title' => 'Conception',
            'groupe' => 'conception',
        ]);
    }

    #[Route('/conception/villa-the-pearl', name: 'conception_pearl')]
    public function conceptionPearl(): Response
    {
        return $this->render('pages/projets/conception/villa-the-pearl.html.twig', [
            'bread_subtitle' => 'Villa plain-pied',
            'bread_title' => 'THE PEARL',
            'projet' => 'the-pearl',
        ]);
    }

    #[Route('/conception/villa-the-big-pearl', name: 'conception_big_pearl')]
    public function conceptionBigPearl(): Response
    {
        return $this->render('pages/projets/conception/villa-the-big-pearl.html.twig', [
            'bread_subtitle' => 'Villa duplex R+1',
            'bread_title' => 'THE BIG PEARL',
            'projet' => 'the-big-pearl',
        ]);
    }

    #[Route('/conception/villa-duplex-bingerville', name: 'conception_duplex_bingerville')]
    public function conceptionDuplexBingerville(): Response
    {
        return $this->render('pages/projets/conception/villa-duplex-bingerville.html.twig', [
            'bread_subtitle' => 'Villa duplex',
            'bread_title' => 'Villa Duplex à Bingerville',
            'projet' => 'duplex-bingerville',
        ]);
    }

    #[Route('/conception/villa-duplex-r1-bingerville', name: 'conception_duplex_r1_bingerville')]
    public function conceptionDuplexR1Bingerville(): Response
    {
        return $this->render('pages/projets/conception/villa-duplex-r1-bingerville.html.twig', [
            'bread_subtitle' => 'Villa duplex R+1',
            'bread_title' => 'Villa Duplex R+1 à Bingerville',
            'projet' => 'duplex-r1-bingerville',
        ]);
    }

    #[Route('/conception/villa-plain-pied-bingerville', name: 'conception_plain_pied_bingerville')]
    public function conceptionPlainPiedBingerville(): Response
    {
        return $this->render('pages/projets/conception/villa-plain-pied-bingerville.html.twig', [
            'bread_subtitle' => 'Villa plain-pied',
            'bread_title' => 'Villa Plain Pied à Bingerville',
            'projet' => 'plain-pied-bingerville',
        ]);
    }

    // ============================================
    // GROUPE 2 : PROGRAMME IMMOBILIER
    // ============================================
    #[Route('/programme-immobilier', name: 'immobilier')]
    public function immobilier(): Response
    {
        return $this->render('pages/projets/immobilier/index.html.twig', [
            'bread_subtitle' => 'Programmes immobiliers',
            'bread_title' => 'Programme immobilier',
            'groupe' => 'immobilier',
        ]);
    }

    #[Route('/programme-immobilier/eco-terra-city', name: 'immobilier_eco_terra')]
    public function immobilierEcoTerra(): Response
    {
        return $this->render('pages/projets/immobilier/eco-terra-city.html.twig', [
            'bread_subtitle' => 'Éco-quartier',
            'bread_title' => 'Eco-Terra City',
            'projet' => 'eco-terra-city',
        ]);
    }

    #[Route('/programme-immobilier/nestle-ci', name: 'immobilier_nestle')]
    public function immobilierNestle(): Response
    {
        return $this->render('pages/projets/immobilier/nestle-ci.html.twig', [
            'bread_subtitle' => 'Programme d\'entreprise',
            'bread_title' => 'Programme Nestlé CI',
            'projet' => 'nestle-ci',
        ]);
    }

    // ============================================
    // GROUPE 3 : CONSTRUCTION
    // ============================================
    #[Route('/construction', name: 'construction')]
    public function construction(): Response
    {
        return $this->render('pages/projets/construction/index.html.twig', [
            'bread_subtitle' => 'Chantiers & réalisations',
            'bread_title' => 'Construction',
            'groupe' => 'construction',
        ]);
    }

    #[Route('/construction/villa-duplex-r1', name: 'construction_villa_duplex')]
    public function constructionVillaDuplex(): Response
    {
        return $this->render('pages/projets/construction/villa-duplex-r1.html.twig', [
            'bread_subtitle' => 'Villa duplex R+1',
            'bread_title' => 'Villa Duplex R+1',
            'projet' => 'villa-duplex-r1',
        ]);
    }

    #[Route('/construction/harmenien-oci', name: 'construction_oci')]
    public function constructionOci(): Response
    {
        return $this->render('pages/projets/construction/harmenien-oci.html.twig', [
            'bread_subtitle' => 'Grand ensemble',
            'bread_title' => 'Harmenien de OCI',
            'projet' => 'harmenien-oci',
        ]);
    }

    #[Route('/construction/villa-palmera', name: 'construction_palmera')]
    public function constructionPalmera(): Response
    {
        return $this->render('pages/projets/construction/villa-palmera.html.twig', [
            'bread_subtitle' => 'Villa de prestige',
            'bread_title' => 'Villa Palmera',
            'projet' => 'villa-palmera',
        ]);
    }

    // ============================================
    // GROUPE 4 : AUTRES TRAVAUX BTP
    // ============================================
    #[Route('/autres-travaux', name: 'autres')]
    public function autres(): Response
    {
        return $this->render('pages/projets/autres/index.html.twig', [
            'bread_subtitle' => 'Travaux spécialisés',
            'bread_title' => 'Autres travaux BTP',
            'groupe' => 'autres',
        ]);
    }

    #[Route('/autres-travaux/confection-portes-cuisines-dressing', name: 'autres_confection')]
    public function autresConfection(): Response
    {
        return $this->render('pages/projets/autres/confection.html.twig', [
            'bread_subtitle' => 'Menuiserie sur mesure',
            'bread_title' => 'Portes, Cuisines & Dressing',
            'projet' => 'confection',
        ]);
    }

    #[Route('/autres-travaux/amenagement-bureau-giz', name: 'autres_giz')]
    public function autresGiz(): Response
    {
        return $this->render('pages/projets/autres/amenagement-bureau-giz.html.twig', [
            'bread_subtitle' => 'Aménagement de bureau',
            'bread_title' => 'Bureau GIZ',
            'projet' => 'amenagement-giz',
        ]);
    }

    #[Route('/autres-travaux/genie-civil-pk18', name: 'autres_pk18')]
    public function autresPk18(): Response
    {
        return $this->render('pages/projets/autres/genie-civil-pk18.html.twig', [
            'bread_subtitle' => 'Infrastructures lourdes',
            'bread_title' => 'Génie civil PK18',
            'projet' => 'genie-civil-pk18',
        ]);
    }
}