<?php

namespace App\Controller\Admin;

use App\Entity\AMembre;
use App\Form\AMembreForm;
use App\Repository\AMembreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/association/membre')]
final class AMembreController extends AbstractController
{
    #[Route(name: 'app_a_membre_index', methods: ['GET'])]
    public function index(AMembreRepository $aMembreRepository): Response
    {
        return $this->render('admin/association_manager/a_membre/index.html.twig', [
            'a_membres' => $aMembreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_a_membre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $aMembre = new AMembre();
        $form = $this->createForm(AMembreForm::class, $aMembre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($aMembre);
            $entityManager->flush();

            return $this->redirectToRoute('app_a_membre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/association_manager/a_membre/new.html.twig', [
            'a_membre' => $aMembre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_a_membre_show', methods: ['GET'])]
    public function show(AMembre $aMembre): Response
    {
        return $this->render('admin/association_manager/a_membre/show.html.twig', [
            'a_membre' => $aMembre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_a_membre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AMembre $aMembre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AMembreForm::class, $aMembre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_a_membre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/association_manager/a_membre/edit.html.twig', [
            'a_membre' => $aMembre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_a_membre_delete', methods: ['POST'])]
    public function delete(Request $request, AMembre $aMembre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$aMembre->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($aMembre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_a_membre_index', [], Response::HTTP_SEE_OTHER);
    }
}
