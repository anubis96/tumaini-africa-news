<?php
// src/Controller/Admin/CommentController.php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/comments')]
class CommentController extends AbstractController
{
    #[Route('/', name: 'app_admin_comments_index', methods: ['GET'])]
    public function index(CommentRepository $commentRepository, Request $request): Response
    {
        $status = $request->query->get('status', 'pending');
        
        switch ($status) {
            case 'approved':
                $comments = $commentRepository->findBy(['isApproved' => true], ['createdAt' => 'DESC']);
                break;
            case 'rejected':
                $comments = $commentRepository->findBy(['isRejected' => true], ['createdAt' => 'DESC']);
                break;
            case 'all':
                $comments = $commentRepository->findBy([], ['createdAt' => 'DESC']);
                break;
            case 'pending':
            default:
                $comments = $commentRepository->findBy(
                    ['isApproved' => false, 'isRejected' => false], 
                    ['createdAt' => 'ASC']
                );
                break;
        }
        
        return $this->render('admin/comments/index.html.twig', [
            'comments' => $comments,
            'current_status' => $status,
            'pending_count' => $commentRepository->count(['isApproved' => false, 'isRejected' => false]),
            'approved_count' => $commentRepository->count(['isApproved' => true]),
            'rejected_count' => $commentRepository->count(['isRejected' => true]),
            'total_count' => $commentRepository->count([]),
        ]);
    }
    
    #[Route('/{id}/approve', name: 'app_admin_comments_approve', methods: ['POST'])]
    public function approve(Comment $comment, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Récupérer la page de redirection
        $redirectStatus = $request->request->get('redirect_status', 'pending');
        
        // Modifier les statuts
        $comment->setIsApproved(true);
        $comment->setIsRejected(false);
        $entityManager->flush();
        
        $this->addFlash('success', 'Le commentaire de "' . $comment->getFirstname() . ' ' . $comment->getLastname() . '" a été approuvé.');
        
        return $this->redirectToRoute('app_admin_comments_index', ['status' => $redirectStatus]);
    }
    
    #[Route('/{id}/reject', name: 'app_admin_comments_reject', methods: ['POST'])]
    public function reject(Comment $comment, EntityManagerInterface $entityManager, Request $request): Response
    {
        $redirectStatus = $request->request->get('redirect_status', 'pending');
        
        $comment->setIsApproved(false);
        $comment->setIsRejected(true);
        $entityManager->flush();
        
        $this->addFlash('warning', 'Le commentaire a été rejeté.');
        
        return $this->redirectToRoute('app_admin_comments_index', ['status' => $redirectStatus]);
    }
    
    #[Route('/{id}/delete', name: 'app_admin_comments_delete', methods: ['POST'])]
    public function delete(Comment $comment, EntityManagerInterface $entityManager, Request $request): Response
    {
        $token = $request->getPayload()->getString('_token');
        $redirectStatus = $request->request->get('redirect_status', 'pending');
        
        if ($this->isCsrfTokenValid('delete_comment_' . $comment->getId(), $token)) {
            $authorName = $comment->getFirstname() . ' ' . $comment->getLastname();
            $entityManager->remove($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Le commentaire de "' . $authorName . '" a été supprimé.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }
        
        return $this->redirectToRoute('app_admin_comments_index', ['status' => $redirectStatus]);
    }
    
    #[Route('/bulk-action', name: 'app_admin_comments_bulk', methods: ['POST'])]
    public function bulkAction(Request $request, CommentRepository $commentRepository, EntityManagerInterface $entityManager): Response
    {
        $action = $request->getPayload()->getString('action');
        $commentIds = $request->getPayload()->all('comment_ids');
        $redirectStatus = $request->getPayload()->getString('redirect_status', 'pending');
        
        if (empty($commentIds)) {
            $this->addFlash('error', 'Aucun commentaire sélectionné.');
            return $this->redirectToRoute('app_admin_comments_index', ['status' => $redirectStatus]);
        }
        
        if (empty($action)) {
            $this->addFlash('error', 'Veuillez sélectionner une action.');
            return $this->redirectToRoute('app_admin_comments_index', ['status' => $redirectStatus]);
        }
        
        $comments = $commentRepository->findBy(['id' => $commentIds]);
        $count = 0;
        
        foreach ($comments as $comment) {
            switch ($action) {
                case 'approve':
                    $comment->setIsApproved(true);
                    $comment->setIsRejected(false);
                    $count++;
                    break;
                case 'reject':
                    $comment->setIsApproved(false);
                    $comment->setIsRejected(true);
                    $count++;
                    break;
                case 'delete':
                    $entityManager->remove($comment);
                    $count++;
                    break;
            }
        }
        
        $entityManager->flush();
        
        $messages = [
            'approve' => $count . ' commentaire(s) ont été approuvés.',
            'reject' => $count . ' commentaire(s) ont été rejetés.',
            'delete' => $count . ' commentaire(s) ont été supprimés.',
        ];
        
        $this->addFlash('success', $messages[$action] ?? 'Action effectuée avec succès.');
        
        return $this->redirectToRoute('app_admin_comments_index', ['status' => $redirectStatus]);
    }
    
    #[Route('/{id}/view', name: 'app_admin_comments_view', methods: ['GET'])]
    public function view(Comment $comment): Response
    {
        return $this->render('admin/comments/view.html.twig', [
            'comment' => $comment,
        ]);
    }
}