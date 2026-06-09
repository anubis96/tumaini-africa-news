<?php
// src/Twig/AdminExtension.php

namespace App\Twig;

use App\Entity\User;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AdminExtension extends AbstractExtension
{
    private CommentRepository $commentRepository;
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository, CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->userRepository = $userRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_pending_comments_count', [$this, 'getPendingCommentsCount']),
            new TwigFunction('get_inactive_users_count', [$this, 'getInactiveUsersCount'])
        ];
    }

    public function getPendingCommentsCount(): int
    {
        return $this->commentRepository->countPendingComments();
    }

    public function getInactiveUsersCount(): int
    {
        return $this->userRepository->count(['isActive' => false]);
    }

}