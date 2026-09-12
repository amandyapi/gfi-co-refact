<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use App\Repository\BlogRepository;
use App\Service\BlogService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/articles', name: 'app_admin_blog_')]
class AdminBlogController extends AbstractController
{
    public function __construct(
        private BlogService $blogService,
        private EntityManagerInterface $em,
        private SluggerInterface $slugger,
        private string $blogUploadDir
    ) {}

    #[Route('', name: 'index')]
    public function index(BlogRepository $blogRepository): Response
    {
        return $this->render('admin/blog/index.html.twig', [
            'posts' => $blogRepository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/nouveau', name: 'new')]
    public function new(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $data = $this->validateBlogRequest($request);

            if (empty($data['errors'])) {
                $blog = new Blog();
                $this->hydrateBlog($blog, $data, $request);

                $this->blogService->createPost($blog);
                $this->addFlash('success', 'Article créé avec succès.');

                return $this->redirectToRoute('app_admin_blog_index');
            }

            return $this->render('admin/blog/form.html.twig', [
                'isEdit' => false,
                'data' => $data['values'],
                'errors' => $data['errors'],
            ]);
        }

        return $this->render('admin/blog/form.html.twig', [
            'isEdit' => false,
            'data' => [
                'title' => '',
                'slug' => '',
                'category' => '',
                'excerpt' => '',
                'content' => '',
                'locale' => 'fr',
                'publishedAt' => (new \DateTimeImmutable())->format('Y-m-d'),
            ],
            'errors' => [],
        ]);
    }

    #[Route('/{id}/editer', name: 'edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, Blog $blog): Response
    {
        if ($request->isMethod('POST')) {
            $data = $this->validateBlogRequest($request, $blog);

            if (empty($data['errors'])) {
                $this->hydrateBlog($blog, $data, $request);
                $this->blogService->updatePost($blog);
                $this->addFlash('success', 'Article modifié avec succès.');

                return $this->redirectToRoute('app_admin_blog_index');
            }

            return $this->render('admin/blog/form.html.twig', [
                'isEdit' => true,
                'blog' => $blog,
                'data' => $data['values'],
                'errors' => $data['errors'],
            ]);
        }

        return $this->render('admin/blog/form.html.twig', [
            'isEdit' => true,
            'blog' => $blog,
            'data' => [
                'title' => $blog->getTitle(),
                'slug' => $blog->getSlug(),
                'category' => $blog->getCategory(),
                'excerpt' => $blog->getExcerpt(),
                'content' => $blog->getContent(),
                'locale' => $blog->getLocale(),
                'publishedAt' => $blog->getPublishedAt()?->format('Y-m-d'),
            ],
            'errors' => [],
        ]);
    }

    #[Route('/{id}/supprimer', name: 'delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, Blog $blog): Response
    {
        if ($this->isCsrfTokenValid('delete_blog_' . $blog->getId(), $request->request->get('_token'))) {
            $this->blogService->deletePost($blog);
            $this->addFlash('success', 'Article supprimé avec succès.');
        }

        return $this->redirectToRoute('app_admin_blog_index');
    }

    // =====================================================
    // Helpers
    // =====================================================

    private function validateBlogRequest(Request $request, ?Blog $blog = null): array
    {
        $values = [
            'title'       => trim((string) $request->request->get('title', '')),
            'slug'        => trim((string) $request->request->get('slug', '')),
            'category'    => trim((string) $request->request->get('category', '')),
            'excerpt'     => trim((string) $request->request->get('excerpt', '')),
            'content'     => (string) $request->request->get('content', ''),
            'locale'      => (string) $request->request->get('locale', 'fr'),
            'publishedAt' => (string) $request->request->get('publishedAt', ''),
        ];

        $errors = [];

        if ($values['title'] === '') {
            $errors['title'] = 'Le titre est obligatoire.';
        }
        if ($values['content'] === '' || trim(strip_tags($values['content'])) === '') {
            $errors['content'] = 'Le contenu est obligatoire.';
        }
        if (!in_array($values['locale'], ['fr', 'en'], true)) {
            $errors['locale'] = 'Langue invalide.';
        }
        if ($values['publishedAt'] !== '' && !\DateTimeImmutable::createFromFormat('Y-m-d', $values['publishedAt'])) {
            $errors['publishedAt'] = 'Date invalide.';
        }

        return ['values' => $values, 'errors' => $errors];
    }

    private function hydrateBlog(Blog $blog, array $data, Request $request): void
    {
        $values = $data['values'];

        $blog->setTitle($values['title']);
        $blog->setCategory($values['category'] ?: null);
        $blog->setExcerpt($values['excerpt'] ?: null);
        $blog->setContent($values['content']);
        $blog->setLocale($values['locale']);

        // Slug : si vide, on le génère à partir du titre
        $slug = $values['slug'] ?: strtolower($this->slugger->slug($values['title'])->toString());
        $blog->setSlug($slug);

        if ($values['publishedAt'] !== '') {
            $blog->setPublishedAt(new \DateTimeImmutable($values['publishedAt']));
        }

        /** @var UploadedFile|null $file */
        $file = $request->files->get('imageFile');
        if ($file instanceof UploadedFile) {
            $filename = uniqid() . '.' . $file->guessExtension();
            $file->move($this->blogUploadDir, $filename);
            $blog->setImageUrl($filename);
        }
    }
}