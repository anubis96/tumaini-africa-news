<?php

namespace App\Controller\Admin;

use App\Repository\ArticleRepository;
use App\Repository\AudioRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(
        CategoryRepository $categoryRepository,
        ArticleRepository $articleRepository,
        AudioRepository $audioRepository,
        UserRepository $userRepository,
        CommentRepository $commentRepository
    ): Response
    {
        $categories = $categoryRepository->findAll();
        $articles = $articleRepository->findBy([], ['publishedAt' => 'DESC'], 10);
        $audios = $audioRepository->findBy([], ['publishedAt' => 'DESC'], 5);
        $users = $userRepository->findAll();
        $comments = $commentRepository->countPendingComments();

        $stats = [
            'totalArticles' => count($articleRepository->findAll()),
            'totalUsers' => count($users),
            'totalAudios' => count($audioRepository->findAll()),
            'totalCategories' => count($categoryRepository->findAll())
        ];

        return $this->render('admin/dashboard.html.twig', [
            'controller_name' => 'AdminController',
            'categories' => $categories,
            'articles' => $articles,
            'audios' => $audios,
            'users' => $users,
            'stats' => $stats,
            'pending_count' => $comments
        ]);
    }
}
