<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale}/projets', name: 'app_projets_', requirements: ['_locale' => 'fr|en'])]
class ProjetController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Our achievements',
            'bread_title' => 'All our projects',
        ] : [
            'bread_subtitle' => 'Nos réalisations',
            'bread_title' => 'Tous nos projets',
        ];
        
        return $this->render('pages/projets/index-' . $_locale . '.html.twig', $data);
    }

    // ============================================
    // GROUPE 1 : CONCEPTION
    // ============================================
    #[Route('/conception', name: 'conception')]
    public function conception(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Design and project planning',
            'bread_title' => 'Design',
            'groupe' => 'conception',
        ] : [
            'bread_subtitle' => 'Conception plans et projets',
            'bread_title' => 'Conception',
            'groupe' => 'conception',
        ];
        
        return $this->render('pages/projets/conception/index-' . $_locale . '.html.twig', $data);
    }

    #[Route('/conception/villa-the-pearl', name: 'conception_pearl')]
    public function conceptionPearl(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Single-storey villa',
            'bread_title' => 'THE PEARL',
            'projet' => 'the-pearl',
        ] : [
            'bread_subtitle' => 'Villa plain-pied',
            'bread_title' => 'THE PEARL',
            'projet' => 'the-pearl',
        ];
        
        return $this->render('pages/projets/conception/villa-the-pearl-' . $_locale . '.html.twig', $data);
    }

    #[Route('/conception/villa-the-big-pearl', name: 'conception_big_pearl')]
    public function conceptionBigPearl(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Duplex villa R+1',
            'bread_title' => 'THE BIG PEARL',
            'projet' => 'the-big-pearl',
        ] : [
            'bread_subtitle' => 'Villa duplex R+1',
            'bread_title' => 'THE BIG PEARL',
            'projet' => 'the-big-pearl',
        ];
        
        return $this->render('pages/projets/conception/villa-the-big-pearl-' . $_locale . '.html.twig', $data);
    }

    #[Route('/conception/villa-duplex-bingerville', name: 'conception_duplex_bingerville')]
    public function conceptionDuplexBingerville(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Duplex villa',
            'bread_title' => 'Duplex Villa in Bingerville',
            'projet' => 'duplex-bingerville',
        ] : [
            'bread_subtitle' => 'Villa duplex',
            'bread_title' => 'Villa Duplex à Bingerville',
            'projet' => 'duplex-bingerville',
        ];
        
        return $this->render('pages/projets/conception/villa-duplex-bingerville-' . $_locale . '.html.twig', $data);
    }

    #[Route('/conception/villa-duplex-r1-bingerville', name: 'conception_duplex_r1_bingerville')]
    public function conceptionDuplexR1Bingerville(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Duplex villa R+1',
            'bread_title' => 'Duplex R+1 Villa in Bingerville',
            'projet' => 'duplex-r1-bingerville',
        ] : [
            'bread_subtitle' => 'Villa duplex R+1',
            'bread_title' => 'Villa Duplex R+1 à Bingerville',
            'projet' => 'duplex-r1-bingerville',
        ];
        
        return $this->render('pages/projets/conception/villa-duplex-r1-bingerville-' . $_locale . '.html.twig', $data);
    }

    #[Route('/conception/villa-plain-pied-bingerville', name: 'conception_plain_pied_bingerville')]
    public function conceptionPlainPiedBingerville(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Single-storey villa',
            'bread_title' => 'Single-storey Villa in Bingerville',
            'projet' => 'plain-pied-bingerville',
        ] : [
            'bread_subtitle' => 'Villa plain-pied',
            'bread_title' => 'Villa Plain Pied à Bingerville',
            'projet' => 'plain-pied-bingerville',
        ];
        
        return $this->render('pages/projets/conception/villa-plain-pied-bingerville-' . $_locale . '.html.twig', $data);
    }

    // ============================================
    // GROUPE 2 : PROGRAMME IMMOBILIER
    // ============================================
    #[Route('/programme-immobilier', name: 'immobilier')]
    public function immobilier(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Real estate programs',
            'bread_title' => 'Real estate program',
            'groupe' => 'immobilier',
        ] : [
            'bread_subtitle' => 'Programmes immobiliers',
            'bread_title' => 'Programme immobilier',
            'groupe' => 'immobilier',
        ];
        
        return $this->render('pages/projets/immobilier/index-' . $_locale . '.html.twig', $data);
    }

    #[Route('/programme-immobilier/eco-terra-city', name: 'immobilier_eco_terra')]
    public function immobilierEcoTerra(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Eco-neighborhood',
            'bread_title' => 'Eco-Terra City',
            'projet' => 'eco-terra-city',
        ] : [
            'bread_subtitle' => 'Éco-quartier',
            'bread_title' => 'Eco-Terra City',
            'projet' => 'eco-terra-city',
        ];
        
        return $this->render('pages/projets/immobilier/eco-terra-city-' . $_locale . '.html.twig', $data);
    }

    #[Route('/programme-immobilier/nestle-ci', name: 'immobilier_nestle')]
    public function immobilierNestle(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Company program',
            'bread_title' => 'Nestlé CI Program',
            'projet' => 'nestle-ci',
        ] : [
            'bread_subtitle' => 'Programme d\'entreprise',
            'bread_title' => 'Programme Nestlé CI',
            'projet' => 'nestle-ci',
        ];
        
        return $this->render('pages/projets/immobilier/nestle-ci-' . $_locale . '.html.twig', $data);
    }

    // ============================================
    // GROUPE 3 : CONSTRUCTION
    // ============================================
    #[Route('/construction', name: 'construction')]
    public function construction(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Construction sites',
            'bread_title' => 'Construction',
            'groupe' => 'construction',
        ] : [
            'bread_subtitle' => 'Chantiers & réalisations',
            'bread_title' => 'Construction',
            'groupe' => 'construction',
        ];
        
        return $this->render('pages/projets/construction/index-' . $_locale . '.html.twig', $data);
    }

    #[Route('/construction/villa-duplex-r1', name: 'construction_villa_duplex')]
    public function constructionVillaDuplex(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Duplex villa R+1',
            'bread_title' => 'Duplex R+1 Villa',
            'projet' => 'villa-duplex-r1',
        ] : [
            'bread_subtitle' => 'Villa duplex R+1',
            'bread_title' => 'Villa Duplex R+1',
            'projet' => 'villa-duplex-r1',
        ];
        
        return $this->render('pages/projets/construction/villa-duplex-r1-' . $_locale . '.html.twig', $data);
    }

    #[Route('/construction/harmenien-oci', name: 'construction_oci')]
    public function constructionOci(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Large-scale project',
            'bread_title' => 'Harmenien de OCI',
            'projet' => 'harmenien-oci',
        ] : [
            'bread_subtitle' => 'Grand ensemble',
            'bread_title' => 'Harmenien de OCI',
            'projet' => 'harmenien-oci',
        ];
        
        return $this->render('pages/projets/construction/harmenien-oci-' . $_locale . '.html.twig', $data);
    }



    // ============================================
    // GROUPE 4 : AUTRES TRAVAUX BTP
    // ============================================


    #[Route('/second-oeuvre-amenagements', name: 'autres')]
    public function autres(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Specialized works',
            'bread_title' => 'Other construction works',
            'groupe' => 'Interior & Finishing Works',
        ] : [
            'bread_subtitle' => 'Travaux spécialisés',
            'bread_title' => 'Autres travaux BTP',
            'groupe' => 'Second Œuvre & Aménagements',
        ];
        
        return $this->render('pages/projets/autres/index-' . $_locale . '.html.twig', $data);
    }

    #[Route('/autres/villa-palmera', name: 'autres_palmera')]
    public function constructionPalmera(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Prestige villa',
            'bread_title' => 'Villa Palmera',
            'projet' => 'villa-palmera',
        ] : [
            'bread_subtitle' => 'Villa de prestige',
            'bread_title' => 'Villa Palmera',
            'projet' => 'villa-palmera',
        ];
        
        return $this->render('pages/projets/autres/villa-palmera-' . $_locale . '.html.twig', $data);
    }

    #[Route('/second-oeuvre-amenagements/confection-portes-cuisines-dressing', name: 'autres_confection')]
    public function autresConfection(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Custom woodwork',
            'bread_title' => 'Doors, Kitchens & Dressing',
            'projet' => 'confection',
        ] : [
            'bread_subtitle' => 'Menuiserie sur mesure',
            'bread_title' => 'Portes, Cuisines & Dressing',
            'projet' => 'confection',
        ];
        
        return $this->render('pages/projets/autres/confection-' . $_locale . '.html.twig', $data);
    }

    #[Route('/second-oeuvre-amenagements/amenagement-bureau-giz', name: 'autres_giz')]
    public function autresGiz(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Office renovation',
            'bread_title' => 'GIZ Office',
            'projet' => 'amenagement-giz',
        ] : [
            'bread_subtitle' => 'Aménagement de bureau',
            'bread_title' => 'Bureau GIZ',
            'projet' => 'amenagement-giz',
        ];
        
        return $this->render('pages/projets/autres/amenagement-bureau-giz-' . $_locale . '.html.twig', $data);
    }

    #[Route('/second-oeuvre-amenagements/genie-civil-pk18', name: 'autres_pk18')]
    public function autresPk18(string $_locale = 'fr'): Response
    {
        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Infrastructure',
            'bread_title' => 'Civil engineering PK18',
            'projet' => 'genie-civil-pk18',
        ] : [
            'bread_subtitle' => 'Infrastructures lourdes',
            'bread_title' => 'Génie civil PK18',
            'projet' => 'genie-civil-pk18',
        ];
        
        return $this->render('pages/projets/autres/genie-civil-pk18-' . $_locale . '.html.twig', $data);
    }
}