<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Service\ArticleViewTracker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SingleArticlePublicController extends AbstractController
{
    #[Route('/article/{slug}', name: 'app_single_article_public', requirements: ['slug' => '[a-z0-9\-]+'])]
    public function index(
        String $slug, 
        ArticleRepository $articleRepository,
        ArticleViewTracker $tracker,
        CommentRepository $commentRepository
        ): Response

    {
        $article = $articleRepository->findOneBySlug($slug);
        
        if(!$article){
            throw $this->createNotFoundException("L'article demandé n'existe pas");
        }

        $tracker->track($article);
        
        $relatedArticles = $articleRepository->findRelatedArticles(
            $article->getId(),
            $article->getCategory(),
            3
        );

        $comments = $commentRepository->findApprovedByArticle($article->getId());
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $response = $this->render('single_article_public/index.html.twig', [
            'article' => $article,
            'relatedArticles' => $relatedArticles,
            'comments' => $comments,
            'commentForm' => $form->createView(),
            'controller_name' => 'SingleArticlePublicController'
        ]);
        $response->setPublic();
        $response->setMaxAge(3600);
        $response->headers->addCacheControlDirective('stale-while-revalidate', 60);

        return $response;
        
    }
}
