<?php

namespace App\Controller\Admin;

use App\Entity\AActivity;
use App\Form\AActivityForm;
use App\Repository\AActivityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/association/activity')]
final class AActivityController extends AbstractController
{
    #[Route(name: 'app_a_activity_index', methods: ['GET'])]
    public function index(AActivityRepository $aActivityRepository): Response
    {
        return $this->render('/admin/association_manager/a_activity/index.html.twig', [
            'a_activities' => $aActivityRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_a_activity_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $aActivity = new AActivity();
        $form = $this->createForm(AActivityForm::class, $aActivity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($aActivity);
            $entityManager->flush();

            return $this->redirectToRoute('app_a_activity_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/association_manager/a_activity/new.html.twig', [
            'a_activity' => $aActivity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_a_activity_show', methods: ['GET'])]
    public function show(AActivity $aActivity): Response
    {
        return $this->render('admin/association_manager/a_activity/show.html.twig', [
            'a_activity' => $aActivity,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_a_activity_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AActivity $aActivity, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AActivityForm::class, $aActivity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_a_activity_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/association_manager/a_activity/edit.html.twig', [
            'a_activity' => $aActivity,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_a_activity_delete', methods: ['POST'])]
    public function delete(Request $request, AActivity $aActivity, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$aActivity->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($aActivity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_a_activity_index', [], Response::HTTP_SEE_OTHER);
    }
}
