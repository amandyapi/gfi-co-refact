<?php

namespace App\Repository;

use App\Entity\Blog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Blog>
 */
class BlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blog::class);
    }

    /**
     * Récupère les articles paginés par langue
     */
    public function findPaginatedByLocale(string $locale, int $page = 1, int $limit = 6): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.locale = :locale')
            ->setParameter('locale', $locale)
            ->orderBy('b.publishedAt', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Compte le nombre total d'articles par langue
     */
    public function countByLocale(string $locale): int
    {
        return (int) $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->where('b.locale = :locale')
            ->setParameter('locale', $locale)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Récupère un article par slug et langue
     */
    public function findBySlug(string $slug, string $locale): ?Blog
    {
        return $this->createQueryBuilder('b')
            ->where('b.slug = :slug')
            ->andWhere('b.locale = :locale')
            ->setParameter('slug', $slug)
            ->setParameter('locale', $locale)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Récupère les articles par catégorie et langue
     */
    public function findByCategory(string $category, string $locale): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.category = :category')
            ->andWhere('b.locale = :locale')
            ->setParameter('category', $category)
            ->setParameter('locale', $locale)
            ->orderBy('b.publishedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère tous les articles d'une langue
     */
    public function findAllByLocale(string $locale): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.locale = :locale')
            ->setParameter('locale', $locale)
            ->orderBy('b.publishedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les articles récents (pour la sidebar)
     */
    public function findRecentByLocale(string $locale, int $limit = 4, ?int $excludeId = null): array
    {
        $qb = $this->createQueryBuilder('b')
            ->where('b.locale = :locale')
            ->setParameter('locale', $locale)
            ->orderBy('b.publishedAt', 'DESC')
            ->setMaxResults($limit);

        if ($excludeId !== null) {
            $qb->andWhere('b.id != :excludeId')
                ->setParameter('excludeId', $excludeId);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Recherche des articles par mot-clé dans le titre, l'extrait ou le contenu
     */
    public function search(string $query, string $locale): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.locale = :locale')
            ->andWhere('b.title LIKE :query OR b.excerpt LIKE :query OR b.content LIKE :query')
            ->setParameter('locale', $locale)
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('b.publishedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les catégories distinctes pour une langue
     */
    public function findDistinctCategories(string $locale): array
    {
        $result = $this->createQueryBuilder('b')
            ->select('DISTINCT b.category')
            ->where('b.locale = :locale')
            ->andWhere('b.category IS NOT NULL')
            ->setParameter('locale', $locale)
            ->getQuery()
            ->getScalarResult();

        return array_column($result, 'category');
    }

    /**
     * Récupère les archives groupées par mois/année
     * Utilise une requête SQL native pour DATE_FORMAT
     */
    public function findArchives(string $locale): array
    {
        $conn = $this->getEntityManager()->getConnection();
        
        $sql = "SELECT DATE_FORMAT(published_at, '%Y-%m') as period, COUNT(id) as count 
                FROM blog 
                WHERE locale = :locale 
                AND published_at IS NOT NULL 
                GROUP BY period 
                ORDER BY period DESC";
        
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery(['locale' => $locale]);
        
        return $result->fetchAllAssociative();
    }
}