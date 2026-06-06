<?php
// src/Controller/CommentController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ArticleRepository;
use App\Entity\Comment;
use App\Form\CommentType;

#[Route('/comment')]
final class CommentController extends AbstractController
{
    #[Route('/add/{slug}', name: 'app_comment_add', methods: ['POST'])]
    public function add(Request $request, string $slug, ArticleRepository $articleRepository, EntityManagerInterface $entityManager): Response
    {
        // 1. Vérifier la méthode
        if (!$request->isMethod('POST')) {
            $this->addFlash('error', 'Méthode non autorisée');
            return $this->redirectToRoute('app_home');
        }
        
        // 2. Trouver l'article
        $article = $articleRepository->findOneBy(['slug' => $slug]);
        
        if (!$article) {
            $this->addFlash('error', 'Article non trouvé');
            return $this->redirectToRoute('app_home');
        }

        // 3. Créer le commentaire
        $comment = new Comment();
        
        // 4. Remplir manuellement les données (alternative plus simple)
        $data = $request->request->all();
        
        // Vérifier si les données viennent du formulaire Symfony (avec préfixe 'comment')
        $formData = $data['comment'] ?? $data;
        
        // Remplir le commentaire
        if (isset($formData['firstname'])) {
            $comment->setFirstname($formData['firstname']);
        }
        if (isset($formData['lastname'])) {
            $comment->setLastname($formData['lastname']);
        }
        if (isset($formData['phone'])) {
            $comment->setPhone($formData['phone']);
        }
        if (isset($formData['email'])) {
            $comment->setEmail($formData['email']);
        }
        if (isset($formData['content'])) {
            $comment->setContent($formData['content']);
        }
        
        // Validation manuelle
        $errors = [];
        if (empty($comment->getFirstname())) {
            $errors[] = 'Le prénom est obligatoire';
        }
        if (empty($comment->getLastname())) {
            $errors[] = 'Le nom est obligatoire';
        }
        if (empty($comment->getPhone())) {
            $errors[] = 'Le numéro de téléphone est obligatoire';
        }
        if (empty($comment->getContent())) {
            $errors[] = 'Le commentaire est obligatoire';
        }
        
        // Validation du téléphone RDC
        $phone = $comment->getPhone();
        if ($phone && !preg_match('/^(?:(?:\+243|0)[1-9]\d{7,8}|(?:\+|00)[1-9]\d{7,14})$/', $phone)) {
            $errors[] = 'Format de téléphone invalide. Utilisez +243XXXXXX ou 0XXXXXXX';
        }
        
        if (!empty($errors)) {
            foreach ($errors as $error) {
                $this->addFlash('error', $error);
            }
            return $this->redirectToRoute('app_single_article_public', ['slug' => $article->getSlug()]);
        }
        
        // Ajouter les informations supplémentaires
        $comment->setArticle($article);
        $comment->setIpAddress($request->getClientIp());
        $comment->setUserAgent($request->headers->get('User-Agent'));
        $comment->setIsApproved(false);
        $comment->setCreatedAt(new \DateTimeImmutable());
        
        $entityManager->persist($comment);
        $entityManager->flush();
        
        $this->addFlash('success', 'Votre commentaire a été envoyé et sera publié après modération.');
        
        return $this->redirectToRoute('app_single_article_public', ['slug' => $article->getSlug()]);
    }

    #[Route('/like/{id}', name: 'app_comment_like', methods: ['POST'])]
    public function like(Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $comment->incrementLikes();
        $entityManager->flush();

        return $this->json(['likes' => $comment->getLikes()]);
    }
}