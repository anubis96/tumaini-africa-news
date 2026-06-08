<?php

namespace App\Controller\Admin;

use App\Entity\ACategory;
use App\Form\ACategoryForm;
use App\Repository\ACategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/association/category')]
final class ACategoryController extends AbstractController
{
    #[Route(name: 'app_a_category_index', methods: ['GET'])]
    public function index(ACategoryRepository $aCategoryRepository): Response
    {
        return $this->render('admin/association_manager/a_category/index.html.twig', [
            'categories' => $aCategoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_a_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $aCategory = new ACategory();
        $form = $this->createForm(ACategoryForm::class, $aCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($aCategory);
            $entityManager->flush();

            return $this->redirectToRoute('app_a_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/association_manager/a_category/new.html.twig', [
            'a_category' => $aCategory,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_a_category_show', methods: ['GET'])]
    public function show(ACategory $aCategory): Response
    {
        return $this->render('admin/association_manager/a_category/show.html.twig', [
            'a_category' => $aCategory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_a_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ACategory $aCategory, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ACategoryForm::class, $aCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_a_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/association_manager/a_category/edit.html.twig', [
            'a_category' => $aCategory,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_a_category_delete', methods: ['POST'])]
    public function delete(Request $request, ACategory $aCategory, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$aCategory->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($aCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_a_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
