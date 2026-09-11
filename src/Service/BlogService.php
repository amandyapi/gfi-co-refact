<?php

namespace App\Service;

use App\Entity\Blog;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class BlogService
{
    public function __construct(
        private BlogRepository $blogRepository,
        private EntityManagerInterface $entityManager,
        private SluggerInterface $slugger
    ) {
    }

    /**
     * Récupère les articles paginés pour une langue
     */
    public function getPaginatedPosts(string $locale, int $page = 1, int $limit = 6): array
    {
        $posts = $this->blogRepository->findPaginatedByLocale($locale, $page, $limit);
        $total = $this->blogRepository->countByLocale($locale);

        return [
            'posts' => $posts,
            'total' => $total,
            'totalPages' => (int) ceil($total / $limit),
            'currentPage' => $page,
        ];
    }

    /**
     * Récupère un article par son slug
     */
    public function getPostBySlug(string $slug, string $locale): ?Blog
    {
        return $this->blogRepository->findBySlug($slug, $locale);
    }

    /**
     * Récupère les articles par catégorie
     */
    public function getPostsByCategory(string $category, string $locale): array
    {
        return $this->blogRepository->findByCategory($category, $locale);
    }

    /**
     * Récupère les articles récents
     */
    public function getRecentPosts(string $locale, int $limit = 4, ?int $excludeId = null): array
    {
        return $this->blogRepository->findRecentByLocale($locale, $limit, $excludeId);
    }

    /**
     * Recherche des articles
     */
    public function searchPosts(string $query, string $locale): array
    {
        if (empty(trim($query))) {
            return [];
        }
        return $this->blogRepository->search($query, $locale);
    }

    /**
     * Récupère les catégories distinctes
     */
    public function getCategories(string $locale): array
    {
        return $this->blogRepository->findDistinctCategories($locale);
    }

    /**
     * Récupère les archives
     */
    public function getArchives(string $locale): array
    {
        $archives = $this->blogRepository->findArchives($locale);
        $result = [];

        foreach ($archives as $archive) {
            [$year, $month] = explode('-', $archive['period']);
            $date = new \DateTimeImmutable("$year-$month-01");
            $result[] = [
                'label' => $this->getMonthLabel((int) $month) . ' ' . $year,
                'count' => (int) $archive['count'],
                'period' => $archive['period'],
            ];
        }

        return $result;
    }

    /**
     * Crée un nouvel article
     */
    public function createPost(Blog $blog): Blog
    {
        if (empty($blog->getSlug())) {
            $blog->setSlug($this->generateSlug($blog->getTitle(), $blog->getLocale()));
        }

        $this->entityManager->persist($blog);
        $this->entityManager->flush();

        return $blog;
    }

    /**
     * Met à jour un article
     */
    public function updatePost(Blog $blog): Blog
    {
        $this->entityManager->flush();
        return $blog;
    }

    /**
     * Supprime un article
     */
    public function deletePost(Blog $blog): void
    {
        $this->entityManager->remove($blog);
        $this->entityManager->flush();
    }

    /**
     * Génère un slug unique
     */
    public function generateSlug(string $title, string $locale): string
    {
        $slug = strtolower($this->slugger->slug($title)->toString());
        $originalSlug = $slug;
        $counter = 1;

        while ($this->blogRepository->findBySlug($slug, $locale) !== null) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Retourne le libellé d'un mois en français
     */
    private function getMonthLabel(int $month): string
    {
        $months = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre',
        ];
        return $months[$month] ?? '';
    }
}