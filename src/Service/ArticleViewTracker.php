<?php

namespace App\Service;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ArticleViewTracker
{
    public function __construct(
        private EntityManagerInterface $em
    ){}

    public function track(Article $article)
    {
        $article->incrementCount();
        $this->em->flush();
    }
}