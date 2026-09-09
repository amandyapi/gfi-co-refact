<?php

namespace App\Utils;

class Constants
{
    
    /**
     * 
     */
    const STATUS = [
        'DRAFT' => 'DRAFT', 
        'ACTIVE' => 'ACTIVE', 
        'VALIDATED' => 'VALIDATED',
        'CONFIRMED' => 'CONFIRMED',
        'REJECTED' => 'REJECTED',
    ];


    const ROLES = [
        'SUPERADMIN' => 'SUPERADMIN',
        'MEDICAL_ADMIN' => 'MEDICAL_ADMIN',
        'ADMIN' => 'ADMIN',
        'USER' => 'USER',
    ];

    const ENV = [
        'DEV' => 'DEV',
        'TEST' => 'TEST',
        'PROD' => 'PROD',
        'ALL' => 'ALL',
    ];

    const MIME_TYPE = [
        'PDF' => 'application/pdf',
        'JSON' => 'application/json',
        'PLAIN' => 'text/plain',
        'HTML' => 'text/html',
        'JPEG' => 'image/jpeg',
        'PNG' => 'image/png',
        'GIF' => 'image/gif',
        'ZIP' => 'application/zip',
    ];  

    const CIR_EDITION = [
        '2025' => '2025',
        '2026' => '2026'
    ];

        /**
     * Données statiques des articles du blog
     * Ces données seront remplacées par une base de données plus tard
     */
    public const BLOG_POSTS = [
        'al-habtoor' => [
            'title' => 'Al HABTOOR, le portefeuille de marques de GFI-CO',
            'image' => 'sidebar-blog-1-370x264.jpg',
            'category' => 'Actualité',
            'date' => '29 déc. 2021 à 12h16',
            'excerpt' => 'GFI-CO SARL a noué un partenariat stratégique avec Al Habtoor, un groupe international de renom dans le secteur immobilier.',
            'content' => '<p>GFI-CO SARL a noué un partenariat stratégique avec Al Habtoor, un groupe international de renom dans le secteur immobilier. Ce partenariat permettra à GFI-CO d\'élargir son portefeuille de marques et d\'offrir des solutions encore plus innovantes à ses clients.</p>
                          <p>Al Habtoor est reconnu pour son expertise dans le développement immobilier de luxe et son engagement envers l\'excellence. Cette collaboration renforce la position de GFI-CO sur le marché ouest-africain.</p>
                          <p>Les premiers projets conjoints sont déjà en cours de développement et devraient être annoncés prochainement.</p>',
            'author' => 'GFI-CO GROUP',
            'tags' => ['Partenariat', 'Immobilier', 'Al Habtoor']
        ],
        'expo-dubai' => [
            'title' => 'Expo Dubaï 2020 : GFI-CO à la conquête de nouveaux marchés immobiliers',
            'image' => 'sidebar-blog-2-370x264.jpg',
            'category' => 'Actualité',
            'date' => '29 déc. 2021 à 11h28',
            'excerpt' => 'GFI-CO a participé à l\'Expo Dubaï 2020, un événement mondial majeur qui a réuni les acteurs les plus influents du secteur immobilier international.',
            'content' => '<p>GFI-CO a participé à l\'Expo Dubaï 2020, un événement mondial majeur qui a réuni les acteurs les plus influents du secteur immobilier international.</p>
                          <p>Cette participation a permis à GFI-CO de présenter ses projets innovants et de tisser des liens avec des investisseurs et partenaires potentiels du monde entier.</p>
                          <p>L\'équipe de GFI-CO a également eu l\'opportunité de découvrir les dernières tendances et technologies du secteur, renforçant ainsi sa capacité à innover et à proposer des solutions toujours plus durables et performantes.</p>',
            'author' => 'GFI-CO GROUP',
            'tags' => ['Exposition', 'Immobilier', 'Dubaï', 'Innovation']
        ],
        'dejeuner-affaires' => [
            'title' => 'Déjeuner d\'Affaires',
            'image' => 'sidebar-blog-3-370x264.jpg',
            'category' => 'Actualité',
            'date' => '29 déc. 2021 à 11h21',
            'excerpt' => 'GFI-CO a organisé un déjeuner d\'affaires avec ses principaux partenaires et investisseurs pour discuter des opportunités de développement immobilier en Afrique de l\'Ouest.',
            'content' => '<p>GFI-CO a organisé un déjeuner d\'affaires avec ses principaux partenaires et investisseurs pour discuter des opportunités de développement immobilier en Afrique de l\'Ouest.</p>
                          <p>Cet événement a permis de renforcer les relations existantes et d\'explorer de nouvelles voies de collaboration pour des projets d\'envergure.</p>
                          <p>Les échanges ont porté sur les défis et opportunités du marché immobilier ouest-africain, avec un accent particulier sur la durabilité et l\'innovation.</p>',
            'author' => 'GFI-CO GROUP',
            'tags' => ['Événement', 'Partenariat', 'Affaires']
        ],
        'anniversaire' => [
            'title' => 'Anniversaire GFI CO',
            'image' => 'sidebar-blog-4-370x264.jpg',
            'category' => 'Actualité',
            'date' => '29 déc. 2021 à 11h07',
            'excerpt' => 'GFI-CO a célébré son anniversaire en présence de ses collaborateurs, partenaires et amis.',
            'content' => '<p>GFI-CO a célébré son anniversaire en présence de ses collaborateurs, partenaires et amis. Une occasion de revenir sur le parcours accompli et de partager les perspectives d\'avenir.</p>
                          <p>Depuis sa création, GFI-CO n\'a cessé de grandir et de se développer, porté par une équipe passionnée et engagée. Cet anniversaire a été l\'occasion de célébrer les succès passés et de se projeter vers l\'avenir avec confiance.</p>
                          <p>Les projets à venir sont nombreux et prometteurs, confirmant la dynamique positive de GFI-CO sur le marché immobilier ouest-africain.</p>',
            'author' => 'GFI-CO GROUP',
            'tags' => ['Anniversaire', 'Événement', 'Célébration']
        ],
        'partenariat-archi-union' => [
            'title' => 'Partenariat GFI-CO SARL-Cabinet ARCHI-UNION',
            'image' => 'sidebar-blog-5-370x264.jpg',
            'category' => 'Partenariat',
            'date' => '29 déc. 2021 à 10h56',
            'excerpt' => 'GFI-CO SARL et le Cabinet ARCHI-UNION ont signé un partenariat stratégique visant à renforcer leurs capacités respectives.',
            'content' => '<p>GFI-CO SARL et le Cabinet ARCHI-UNION ont signé un partenariat stratégique visant à renforcer leurs capacités respectives dans le domaine de l\'architecture et de la construction immobilière.</p>
                          <p>Ce partenariat permettra de combiner l\'expertise en construction de GFI-CO avec le savoir-faire architectural d\'ARCHI-UNION pour offrir des solutions intégrées et de qualité supérieure.</p>
                          <p>Les premiers projets communs concernent des programmes immobiliers d\'envergure en Côte d\'Ivoire et en Guinée, avec un accent particulier sur la durabilité et l\'innovation architecturale.</p>',
            'author' => 'GFI-CO GROUP',
            'tags' => ['Partenariat', 'Architecture', 'Construction']
        ],
        'expo-dubai-2' => [
            'title' => 'Expo Dubaï 2020 : GFI-CO à la conquête de nouveaux marchés immobiliers',
            'image' => 'sidebar-blog-6-370x264.jpg',
            'category' => 'Actualité',
            'date' => '24 déc. 2021 à 11h41',
            'excerpt' => 'Retour sur la participation de GFI-CO à l\'Expo Dubaï 2020, un événement qui a marqué un tournant dans le développement international de l\'entreprise.',
            'content' => '<p>Retour sur la participation de GFI-CO à l\'Expo Dubaï 2020, un événement qui a marqué un tournant dans le développement international de l\'entreprise.</p>
                          <p>Cette présence sur la scène mondiale a permis à GFI-CO de faire connaître son savoir-faire et ses réalisations auprès d\'un public international, ouvrant la voie à de nouvelles opportunités d\'affaires.</p>
                          <p>L\'équipe de GFI-CO a pu échanger avec des acteurs majeurs du secteur immobilier mondial, renforçant ainsi sa position et sa crédibilité sur le marché international.</p>',
            'author' => 'GFI-CO GROUP',
            'tags' => ['Exposition', 'Immobilier', 'Dubaï', 'International']
        ]
    ];

    /**
     * Retourne tous les articles du blog
     */
    public static function getBlogPosts(): array
    {
        return self::BLOG_POSTS;
    }

    /**
     * Retourne un article par son slug
     */
    public static function getBlogPost(string $slug): ?array
    {
        return self::BLOG_POSTS[$slug] ?? null;
    }

    /**
     * Retourne les articles paginés
     */
    public static function getBlogPostsPaginated(int $page = 1, int $limit = 6): array
    {
        $posts = self::BLOG_POSTS;
        $total = count($posts);
        $offset = ($page - 1) * $limit;
        
        return array_slice($posts, $offset, $limit, true);
    }

    /**
     * Retourne le nombre total d'articles
     */
    public static function getTotalBlogPosts(): int
    {
        return count(self::BLOG_POSTS);
    }

    /**
     * Retourne les catégories uniques
     */
    public static function getBlogCategories(): array
    {
        $categories = [];
        foreach (self::BLOG_POSTS as $post) {
            // On prend la version française par défaut pour les catégories
            $category = is_array($post['category']) ? $post['category']['fr'] : $post['category'];
            if (!in_array($category, $categories)) {
                $categories[] = $category;
            }
        }
        return $categories;
    }

    /**
     * Retourne tous les tags uniques
     */
    public static function getBlogTags(): array
    {
        $tags = [];
        foreach (self::BLOG_POSTS as $post) {
            if (isset($post['tags'])) {
                foreach ($post['tags'] as $tag) {
                    if (!in_array($tag, $tags)) {
                        $tags[] = $tag;
                    }
                }
            }
        }
        return $tags;
    }

    /**
     * Retourne les articles par catégorie
     */
    public static function getBlogPostsByCategory(string $category): array
    {
        $posts = [];
        foreach (self::BLOG_POSTS as $slug => $post) {
            $postCategory = is_array($post['category']) ? $post['category']['fr'] : $post['category'];
            if ($postCategory === $category) {
                $posts[$slug] = $post;
            }
        }
        return $posts;
    }

    /**
     * Retourne les articles par tag
     */
    public static function getBlogPostsByTag(string $tag): array
    {
        $posts = [];
        foreach (self::BLOG_POSTS as $slug => $post) {
            if (isset($post['tags']) && in_array($tag, $post['tags'])) {
                $posts[$slug] = $post;
            }
        }
        return $posts;
    }

    /**
     * Retourne le titre d'un article dans la langue demandée
     */
    public static function getPostTitle(array $post, string $locale = 'fr'): string
    {
        return is_array($post['title']) ? ($post['title'][$locale] ?? $post['title']['fr']) : $post['title'];
    }

    /**
     * Retourne la catégorie d'un article dans la langue demandée
     */
    public static function getPostCategory(array $post, string $locale = 'fr'): string
    {
        return is_array($post['category']) ? ($post['category'][$locale] ?? $post['category']['fr']) : $post['category'];
    }

    /**
     * Retourne l'extrait d'un article dans la langue demandée
     */
    public static function getPostExcerpt(array $post, string $locale = 'fr'): string
    {
        return is_array($post['excerpt']) ? ($post['excerpt'][$locale] ?? $post['excerpt']['fr']) : $post['excerpt'];
    }

    /**
     * Retourne le contenu d'un article dans la langue demandée
     */
    public static function getPostContent(array $post, string $locale = 'fr'): string
    {
        return is_array($post['content']) ? ($post['content'][$locale] ?? $post['content']['fr']) : $post['content'];
    }
}
