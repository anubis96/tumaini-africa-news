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
            $aActivity->setSlug($this->generateSlug($aActivity->getTitle()));
            $aActivity->setUpdatedAt(new \DateTimeImmutable());
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
            $aActivity->setSlug($this->generateSlug($aActivity->getTitle()));
            $aActivity->setUpdatedAt(new \DateTimeImmutable());
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
