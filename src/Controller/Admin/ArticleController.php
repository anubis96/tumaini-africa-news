<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Tag;
use App\Entity\User;
use App\Form\ArticleForm;
use App\Repository\ArticleRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/article')]
final class ArticleController extends AbstractController
{
    #[Route(name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository, Request $request, EntityManagerInterface $em): Response
    {
        $page = $request->query->getInt('page', 1);
    
        // Récupérer le filtre auteur
        $authorFilter = $request->query->get('author');
        $currentUser = $this->getUser();
        
        // Construction de la requête
        $qb = $articleRepository->createQueryBuilder('a')
            ->orderBy('a.createdAt', 'DESC');
        
        // Filtrer par auteur
        if ($authorFilter === 'me') {
            // Ne montrer que les articles de l'utilisateur connecté
            $qb->andWhere('a.author = :author')
            ->setParameter('author', $currentUser);
            $currentUserFilter = true;
            $selectedAuthorId = $currentUser->getId();
        } elseif ($authorFilter) {
            // Filtrer par un auteur spécifique
            $qb->andWhere('a.author = :author')
            ->setParameter('author', $authorFilter);
            $currentUserFilter = false;
            $selectedAuthorId = (int) $authorFilter;
        } else {
            // Montrer tous les articles
            $currentUserFilter = false;
            $selectedAuthorId = null;
        }
        
        // Pagination
        $articles = $articleRepository->paginateQuery($qb, $page);
        
        // Liste des auteurs pour le filtre
        $authors = $em->getRepository(User::class)->findAll();

        $page = $request->query->getInt('page', 1); // Récupère le numéro de page depuis l'URL
        
        $articles = $articleRepository->findAllPaginated($page);
        return $this->render('admin/article/index.html.twig', [
            'articles' => $articles,
            'authors_list' => $authors,
            'current_user_filter' => $currentUserFilter,
            'selected_author_id' => $selectedAuthorId,
        ]);
    }

    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleForm::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ⚠️ Récupérer les tags sélectionnés
            $selectedTags = $form->get('tags')->getData();
            
            // ⚠️ Forcer la persistance des tags en les récupérant depuis le repository
            $tagIds = [];
            if ($selectedTags) {
                foreach ($selectedTags as $tag) {
                    $tagIds[] = $tag->getId();
                }
            }
            
            // Récupérer les tags depuis la base (pour éviter les problèmes de détachement)
            $tagsToAdd = [];
            if (!empty($tagIds)) {
                $tagsToAdd = $entityManager->getRepository(Tag::class)->findBy(['id' => $tagIds]);
            }
            
            // Ajouter les tags à l'article
            foreach ($tagsToAdd as $tag) {
                $article->addTag($tag);
                $tag->setUsageCount($tag->getUsageCount() + 1);
            }

            // ... reste du code
            $slug = $this->generateSlug($article->getTitle());
            $existingArticle = $entityManager->getRepository(Article::class)->findOneBy(['slug' => $slug]);
            if($existingArticle){
                $this->addFlash('error', 'Un article avec ce titre existe déjà');
                return $this->redirectToRoute('app_article_new');
            }

            $article->setCreatedAt(new DateTimeImmutable());
            if ($article->getIsPublished()) {
                $article->setPublishedAt(new DateTimeImmutable());
            }
            $article->setSlug($slug);
            $article->setAuthor($this->getUser());
            
            $entityManager->persist($article);
            $entityManager->flush();

            // Debug - Vérifier que les tags sont bien associés
            $savedArticle = $entityManager->getRepository(Article::class)->find($article->getId());
            dump('Tags après sauvegarde: ' . $savedArticle->getTags()->count());

            $this->addFlash('success', 'Article créé avec succès !');
            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('admin/article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $oldTags = $article->getTags()->toArray();
        $form = $this->createForm(ArticleForm::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
 
            $newTags = $form->get('tags')->getData();
            $newTagsArray = $newTags->toArray();

            $tagsToRemove = array_filter($oldTags, function($oldTag) use ($newTagsArray) {
                return !in_array($oldTag, $newTagsArray);
            });
            
            $tagsToAdd = array_filter($newTagsArray, function($newTag) use ($oldTags) {
                return !in_array($newTag, $oldTags);
            });
            
            foreach ($tagsToRemove as $tagToRemove) {
                $article->removeTag($tagToRemove);
                $tagToRemove->setUsageCount(max(0, $tagToRemove->getUsageCount() - 1));
            }
            
            foreach ($tagsToAdd as $tagToAdd) {
                $article->addTag($tagToAdd);
                $tagToAdd->setUsageCount($tagToAdd->getUsageCount() + 1);
            }
            // Gestion de la publication
            
            if ($article->getIsPublished() && !$article->getPublishedAt()) {
                $article->setPublishedAt(new DateTimeImmutable());
            } elseif (!$article->getIsPublished()) {
                $article->setPublishedAt(null);
            }
            $article->setSlug($this->generateSlug($article->getTitle()));
            $article->setAuthor($this->getUser());
            $article->setUpdatedAt(new DateTimeImmutable());
            $entityManager->flush();

            $this->addFlash('success', 'Article modifié avec succès !');
            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }

    function generateSlug(string $string): string
    {
        // Convertir en minuscules
        $string = strtolower($string);

        // Remplacer les caractères accentués par leur version non accentuée
        $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);

        // Supprimer les caractères spéciaux
        $string = preg_replace('/[^a-z0-9-]/', '-', $string);

        // Supprimer les tirets en trop
        $string = preg_replace('/-+/', '-', $string);

        // Enlever les tirets en début et fin de chaîne
        $string = trim($string, '-');

        return $string;
    }
}
