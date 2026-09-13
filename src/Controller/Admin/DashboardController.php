<?php

namespace App\Controller\Admin;

use App\Repository\BlogRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'app_admin_')]
class DashboardController extends AbstractController
{
    public function __construct(
        private string $blogUploadDir
    ) {}

    #[Route('', name: 'dashboard')]
    public function index(BlogRepository $blogRepository, UserRepository $userRepository): Response
    {
        $now = new \DateTimeImmutable();
        $startOfMonth = $now->modify('first day of this month')->setTime(0, 0);

        // Stats blog
        $localeCounts = $blogRepository->countGroupedByLocale();
        $categories = $blogRepository->countGroupedByCategory(5);
        $publishedThisMonth = $blogRepository->countPublishedSince($startOfMonth);
        $scheduled = $blogRepository->countScheduled();
        $latestPosts = $blogRepository->findLatest(5);

        // Stats users
        $totalUsers = $userRepository->count([]);
        $totalAdmins = $userRepository->countByRole('ROLE_ADMIN');
        $inactiveUsers = $userRepository->count(['isActive' => false]);

        // Infos système
        $uploadsSize = $this->getDirectorySize($this->blogUploadDir);

        return $this->render('admin/dashboard/index.html.twig', [
            // Blog
            'totalPosts'         => $localeCounts['fr'] + $localeCounts['en'],
            'postsFr'            => $localeCounts['fr'],
            'postsEn'            => $localeCounts['en'],
            'publishedThisMonth' => $publishedThisMonth,
            'scheduledPosts'     => $scheduled,
            'topCategories'      => $categories,
            'latestPosts'        => $latestPosts,

            // Users
            'totalUsers'    => $totalUsers,
            'totalAdmins'   => $totalAdmins,
            'inactiveUsers' => $inactiveUsers,

            // Système
            'phpVersion'   => PHP_VERSION,
            'environment'  => $this->getParameter('kernel.environment'),
            'uploadsSize'  => $uploadsSize,
        ]);
    }

    /**
     * Taille d'un dossier en octets (récursif).
     */
    private function getDirectorySize(string $dir): int
    {
        if (!is_dir($dir)) {
            return 0;
        }

        $size = 0;
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }

        return $size;
    }
}