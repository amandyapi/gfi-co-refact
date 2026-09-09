<?php

namespace App\Controller;

use App\Utils\Constants;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale}/actualites', name: 'app_blog_', requirements: ['_locale' => 'fr|en'])]
class BlogController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, string $_locale = 'fr'): Response
    {
        // Récupérer le numéro de page
        $page = (int) $request->query->get('page', 1);
        $limit = 6;
        
        // Récupérer les articles paginés
        $posts = Constants::getBlogPostsPaginated($page, $limit);
        $total = Constants::getTotalBlogPosts();
        $totalPages = ceil($total / $limit);
        
        // Récupérer les catégories et tags pour la sidebar
        $categories = Constants::getBlogCategories();
        $tags = Constants::getBlogTags();
        $archives = $this->getArchives();

        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Our news',
            'bread_title' => 'Blog & news',
        ] : [
            'bread_subtitle' => 'Nos actualités',
            'bread_title' => 'Blog & actualités',
        ];

        return $this->render('pages/blog/blog-' . $_locale . '.html.twig', array_merge($data, [
            'posts' => $posts,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'categories' => $categories,
            'tags' => $tags,
            'archives' => $archives,
        ]));
    }

    #[Route('/{slug}', name: 'single')]
    public function single(string $_locale = 'fr', string $slug): Response
    {
        // Récupérer l'article par son slug
        $post = Constants::getBlogPost($slug);
        
        // Vérifier si l'article existe
        if (!$post) {
            throw $this->createNotFoundException($_locale === 'en' ? 'Article not found' : 'Article non trouvé');
        }
        
        // Récupérer les articles récents pour la sidebar
        $recentPosts = array_slice(Constants::getBlogPosts(), 0, 3, true);

        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Article',
            'bread_title' => 'Read article',
        ] : [
            'bread_subtitle' => 'Article',
            'bread_title' => 'Lire l\'article',
        ];

        return $this->render('pages/blog/blog-single-' . $_locale . '.html.twig', array_merge($data, [
            'post' => $post,
            'slug' => $slug,
            'recentPosts' => $recentPosts,
        ]));
    }

    #[Route('/categorie/{category}', name: 'category')]
    public function category(string $_locale = 'fr', string $category): Response
    {
        $posts = Constants::getBlogPostsByCategory($category);
        
        if (empty($posts)) {
            throw $this->createNotFoundException($_locale === 'en' ? 'Category not found' : 'Catégorie non trouvée');
        }

        $categories = Constants::getBlogCategories();
        $tags = Constants::getBlogTags();
        $archives = $this->getArchives();

        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Category',
            'bread_title' => 'Articles in ' . $category,
        ] : [
            'bread_subtitle' => 'Catégorie',
            'bread_title' => 'Articles dans ' . $category,
        ];

        return $this->render('pages/blog/blog-' . $_locale . '.html.twig', array_merge($data, [
            'posts' => $posts,
            'category' => $category,
            'categories' => $categories,
            'tags' => $tags,
            'archives' => $archives,
            'currentPage' => 1,
            'totalPages' => 1,
        ]));
    }

    #[Route('/tag/{tag}', name: 'tag')]
    public function tag(string $_locale = 'fr', string $tag): Response
    {
        $posts = Constants::getBlogPostsByTag($tag);
        
        if (empty($posts)) {
            throw $this->createNotFoundException($_locale === 'en' ? 'Tag not found' : 'Tag non trouvé');
        }

        $categories = Constants::getBlogCategories();
        $tags = Constants::getBlogTags();
        $archives = $this->getArchives();

        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Tag',
            'bread_title' => 'Articles tagged "' . $tag . '"',
        ] : [
            'bread_subtitle' => 'Tag',
            'bread_title' => 'Articles tagués "' . $tag . '"',
        ];

        return $this->render('pages/blog/blog-' . $_locale . '.html.twig', array_merge($data, [
            'posts' => $posts,
            'tag' => $tag,
            'categories' => $categories,
            'tags' => $tags,
            'archives' => $archives,
            'currentPage' => 1,
            'totalPages' => 1,
        ]));
    }

    #[Route('/recherche', name: 'search')]
    public function search(Request $request, string $_locale = 'fr'): Response
    {
        $query = $request->query->get('s', '');
        $posts = [];
        
        if (!empty($query)) {
            $allPosts = Constants::getBlogPosts();
            foreach ($allPosts as $slug => $post) {
                if (stripos($post['title'], $query) !== false || 
                    stripos($post['content'], $query) !== false ||
                    stripos($post['excerpt'], $query) !== false) {
                    $posts[$slug] = $post;
                }
            }
        }

        $categories = Constants::getBlogCategories();
        $tags = Constants::getBlogTags();
        $archives = $this->getArchives();

        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Search',
            'bread_title' => 'Search results',
        ] : [
            'bread_subtitle' => 'Recherche',
            'bread_title' => 'Résultats de recherche',
        ];

        return $this->render('pages/blog/blog-' . $_locale . '.html.twig', array_merge($data, [
            'posts' => $posts,
            'searchQuery' => $query,
            'categories' => $categories,
            'tags' => $tags,
            'archives' => $archives,
            'currentPage' => 1,
            'totalPages' => 1,
        ]));
    }

    /**
     * Génère les archives par année/mois (statique)
     */
    private function getArchives(): array
    {
        return [
            ['label' => 'Décembre 2021', 'count' => 6],
            ['label' => 'Novembre 2021', 'count' => 0],
            ['label' => 'Octobre 2021', 'count' => 0],
            ['label' => 'Septembre 2021', 'count' => 0],
            ['label' => 'Août 2021', 'count' => 0],
        ];
    }
}