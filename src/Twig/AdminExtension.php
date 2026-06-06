<?php
// src/Twig/AdminExtension.php

namespace App\Twig;

use App\Repository\CommentRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AdminExtension extends AbstractExtension
{
    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_pending_comments_count', [$this, 'getPendingCommentsCount']),
        ];
    }

    public function getPendingCommentsCount(): int
    {
        return $this->commentRepository->countPendingComments();
    }
}