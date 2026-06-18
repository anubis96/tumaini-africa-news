<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\ArticleRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TagClientController extends AbstractController
{
    #[Route('/tags', name: 'app_tags_cloud')]
    public function cloud(TagRepository $tagRepository, Request $request): Response
    {
        $category = $request->query->get('category');
        
        $criteria = ['isActive' => true];
        if ($category) {
            $criteria['category'] = $category;
        }
        
        $tags = $tagRepository->findBy(
            $criteria,
            ['usageCount' => 'DESC', 'name' => 'ASC']
        );
        
        // Grouper par catégorie pour les filtres
        $categories = [];
        $allTags = $tagRepository->findBy(['isActive' => true]);
        foreach ($allTags as $tag) {
            if ($tag->getCategory() && !in_array($tag->getCategory(), $categories)) {
                $categories[] = $tag->getCategory();
            }
        }
        
        return $this->render('tag_client/cloud.html.twig', [
            'tags' => $tags,
            'categories' => $categories,
            'selected_category' => $category,
        ]);
    }

    #[Route('/tag/{slug}', name: 'app_tag_client_show')]
    public function show(
        string $slug, 
        TagRepository $tagRepository, 
        ArticleRepository $articleRepository,
        Request $request
    ): Response {
        $tag = $tagRepository->findOneBy(['slug' => $slug, 'isActive' => true]);
        
        if (!$tag) {
            throw $this->createNotFoundException('Tag non trouvé');
        }
        
        // Pagination
        $page = $request->query->getInt('page', 1);
        $limit = 9;
        
        // Récupérer les articles liés à ce tag (avec pagination)
        $articles = $articleRepository->findArticlesByTag($tag, $page, $limit);
        $totalArticles = $articleRepository->countArticlesByTag($tag);
        $totalPages = ceil($totalArticles / $limit);
        
        // Tags similaires (même catégorie)
        $similarTags = $tagRepository->findBy(
            ['category' => $tag->getCategory(), 'isActive' => true],
            ['usageCount' => 'DESC'],
            10
        );
        
        // Exclure le tag actuel
        $similarTags = array_filter($similarTags, function($t) use ($tag) {
            return $t->getId() !== $tag->getId();
        });
        
        return $this->render('tag_client/index.html.twig', [
            'tag' => $tag,
            'articles' => $articles,
            'similar_tags' => array_slice($similarTags, 0, 8),
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_articles' => $totalArticles,
        ]);
    }

    /**
     * Récupère les articles d'un tag via API (pour AJAX)
     */
    // #[Route('/tag/{id}/articles', name: 'app_tag_articles_api', methods: ['GET'])]
    // public function getArticlesByTag(
    //     int $id, 
    //     ArticleRepository $articleRepository, 
    //     Request $request
    // ): Response {
    //     $page = $request->query->getInt('page', 1);
    //     $limit = $request->query->getInt('limit', 9);
        
    //     // Récupérer le tag
    //     $tag = $this->entityManager->getRepository(Tag::class)->find($id);
    //     if (!$tag) {
    //         return $this->json(['error' => 'Tag non trouvé'], 404);
    //     }
        
    //     $articles = $articleRepository->findArticlesByTag($tag, $page, $limit);
    //     $total = $articleRepository->countArticlesByTag($tag);
        
    //     // Formater les articles pour l'API
    //     $data = [];
    //     foreach ($articles as $article) {
    //         $data[] = [
    //             'id' => $article->getId(),
    //             'title' => $article->getTitle(),
    //             'slug' => $article->getSlug(),
    //             'image' => $article->getImageUrl() ? '/uploads/articles/' . $article->getImageUrl() : null,
    //             'category' => $article->getCategory() ? $article->getCategory()->getName() : null,
    //             'publishedAt' => $article->getPublishedAt()?->format('Y-m-d H:i:s'),
    //         ];
    //     }
        
    //     return $this->json([
    //         'articles' => $data,
    //         'total' => $total,
    //         'page' => $page,
    //         'totalPages' => ceil($total / $limit),
    //     ]);
    // }
    // #[Route('/tags', name: 'app_tags_client_cloud')]
    // public function cloud(TagRepository $tagRepository, Request $request): Response
    // {
    //     $category = $request->query->get('category');
        
    //     $criteria = ['isActive' => true];
    //     if ($category) {
    //         $criteria['category'] = $category;
    //     }
        
    //     $tags = $tagRepository->findBy(
    //         $criteria,
    //         ['usageCount' => 'DESC', 'name' => 'ASC']
    //     );
        
    //     // Grouper par catégorie pour les filtres
    //     $categories = [];
    //     $allTags = $tagRepository->findBy(['isActive' => true]);
    //     foreach ($allTags as $tag) {
    //         if ($tag->getCategory() && !in_array($tag->getCategory(), $categories)) {
    //             $categories[] = $tag->getCategory();
    //         }
    //     }
        
    //     return $this->render('tag_client/cloud.html.twig', [
    //         'tags' => $tags,
    //         'categories' => $categories,
    //         'selected_category' => $category,
    //     ]);
    // }

    // #[Route('/tags/{slug}', name: 'app_tag_client_show')]
    // public function show(string $slug, TagRepository $tagRepository, ArticleRepository $articleRepository): Response
    // {
    //     $tag = $tagRepository->findOneBy(['slug' => $slug, 'isActive' => true]);
        
    //     if (!$tag) {
    //         throw $this->createNotFoundException('Tag non trouvé');
    //     }
        
    //     // Récupérer les articles liés à ce tag
    //     $articles = $articleRepository->findByTags($tag);
        
    //     // Tags similaires (même catégorie)
    //     $similarTags = $tagRepository->findBy(
    //         ['category' => $tag->getCategory(), 'isActive' => true],
    //         ['usageCount' => 'DESC'],
    //         10
    //     );
        
    //     // Exclure le tag actuel
    //     $similarTags = array_filter($similarTags, function($t) use ($tag) {
    //         return $t->getId() !== $tag->getId();
    //     });
        
    //     return $this->render('tag_client/index.html.twig', [
    //         'tag' => $tag,
    //         'articles' => $articles,
    //         'similar_tags' => array_slice($similarTags, 0, 8),
    //     ]);
    // }

    // #[Route('/tag/client', name: 'app_tag_client')]
    // public function index(): Response
    // {
    //     return $this->render('tag_client/index.html.twig', [
    //         'controller_name' => 'TagClientController',
    //     ]);
    // }
}
