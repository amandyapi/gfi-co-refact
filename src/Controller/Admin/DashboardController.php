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
    #[Route('', name: 'dashboard')]
    public function index(BlogRepository $blogRepository, UserRepository $userRepository): Response
    {
        return $this->render('admin/dashboard/index.html.twig', [
            'totalPosts' => $blogRepository->count([]),
            'totalUsers' => $userRepository->count([]),
            'totalAdmins' => $userRepository->countByRole('ROLE_ADMIN'),
        ]);
    }
}