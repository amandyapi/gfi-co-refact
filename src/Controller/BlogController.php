<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Service\BlogService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale}/actualites', name: 'app_blog_', requirements: ['_locale' => 'fr|en'])]
class BlogController extends AbstractController
{
    public function __construct(
        private BlogService $blogService
    ) {
    }

    #[Route('/', name: 'index')]
    public function index(Request $request, string $_locale = 'fr'): Response
    {
        $page = max(1, (int) $request->query->get('page', 1));
        $limit = 6;

        $pagination = $this->blogService->getPaginatedPosts($_locale, $page, $limit);

        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Our news',
            'bread_title' => 'Blog & news',
        ] : [
            'bread_subtitle' => 'Nos actualités',
            'bread_title' => 'Blog & actualités',
        ];

        return $this->render('pages/blog/blog-' . $_locale . '.html.twig', array_merge($data, [
            'posts' => $pagination['posts'],
            'currentPage' => $pagination['currentPage'],
            'totalPages' => $pagination['totalPages'],
            'total' => $pagination['total'],
            'categories' => $this->blogService->getCategories($_locale),
            'archives' => $this->blogService->getArchives($_locale),
        ]));
    }

    #[Route('/{slug}', name: 'single', requirements: ['slug' => '[a-z0-9-]+'])]
    public function single(string $_locale = 'fr', string $slug = ''): Response
    {
        $post = $this->blogService->getPostBySlug($slug, $_locale);

        if (!$post) {
            throw $this->createNotFoundException(
                $_locale === 'en' ? 'Article not found' : 'Article non trouvé'
            );
        }

        $recentPosts = $this->blogService->getRecentPosts($_locale, 4, $post->getId());

        $data = $_locale === 'en' ? [
            'bread_subtitle' => 'Article',
            'bread_title' => $post->getTitle(),
        ] : [
            'bread_subtitle' => 'Article',
            'bread_title' => $post->getTitle(),
        ];

        return $this->render('pages/blog/blog-single-' . $_locale . '.html.twig', array_merge($data, [
            'post' => $post,
            'slug' => $slug,
            'recentPosts' => $recentPosts,
            'categories' => $this->blogService->getCategories($_locale),
        ]));
    }

    #[Route('/categorie/{category}', name: 'category')]
    public function category(string $_locale = 'fr', string $category = ''): Response
    {
        $posts = $this->blogService->getPostsByCategory($category, $_locale);

        if (empty($posts)) {
            throw $this->createNotFoundException(
                $_locale === 'en' ? 'Category not found' : 'Catégorie non trouvée'
            );
        }

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
            'categories' => $this->blogService->getCategories($_locale),
            'archives' => $this->blogService->getArchives($_locale),
            'currentPage' => 1,
            'totalPages' => 1,
        ]));
    }

    #[Route('/recherche', name: 'search')]
    public function search(Request $request, string $_locale = 'fr'): Response
    {
        $query = $request->query->get('s', '');
        $posts = $this->blogService->searchPosts($query, $_locale);

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
            'categories' => $this->blogService->getCategories($_locale),
            'archives' => $this->blogService->getArchives($_locale),
            'currentPage' => 1,
            'totalPages' => 1,
        ]));
    }

    // ============================================
    // ADMIN - CRUD Articles
    // ============================================

    #[Route('/admin/new', name: 'admin_new', priority: 10)]
    public function adminNew(Request $request, string $_locale = 'fr'): Response
    {
        $blog = new Blog();
        $blog->setLocale($_locale);

        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $blog->setImageFile($imageFile);
            }

            $this->blogService->createPost($blog);
            $this->addFlash('success', 'Article créé avec succès');

            return $this->redirectToRoute('app_blog_index', ['_locale' => $_locale]);
        }

        return $this->render('pages/blog/admin/form-' . $_locale . '.html.twig', [
            'form' => $form->createView(),
            'blog' => $blog,
            'isEdit' => false,
        ]);
    }

    #[Route('/admin/{id}/edit', name: 'admin_edit', requirements: ['id' => '\d+'], priority: 10)]
    public function adminEdit(Request $request, Blog $blog, string $_locale = 'fr'): Response
    {
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $blog->setImageFile($imageFile);
            }

            $this->blogService->updatePost($blog);
            $this->addFlash('success', 'Article modifié avec succès');

            return $this->redirectToRoute('app_blog_index', ['_locale' => $_locale]);
        }

        return $this->render('pages/blog/admin/form-' . $_locale . '.html.twig', [
            'form' => $form->createView(),
            'blog' => $blog,
            'isEdit' => true,
        ]);
    }

    #[Route('/admin/{id}/delete', name: 'admin_delete', requirements: ['id' => '\d+'], methods: ['POST'], priority: 10)]
    public function adminDelete(Request $request, Blog $blog, string $_locale = 'fr'): Response
    {
        if ($this->isCsrfTokenValid('delete' . $blog->getId(), $request->request->get('_token'))) {
            $this->blogService->deletePost($blog);
            $this->addFlash('success', 'Article supprimé avec succès');
        }

        return $this->redirectToRoute('app_blog_index', ['_locale' => $_locale]);
    }
}