<?php

namespace App\Controller\Admin;

use App\Entity\AOffre;
use App\Form\AOffreForm;
use App\Repository\AOffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/association/offre')]
final class AOffreController extends AbstractController
{
    #[Route(name: 'app_a_offre_index', methods: ['GET'])]
    public function index(AOffreRepository $aOffreRepository): Response
    {
        return $this->render('admin/association_manager/a_offre/index.html.twig', [
            'a_offres' => $aOffreRepository->findAll(),
            'types_list' => AOffre::getTypesList(),
            'statuts_list' => AOffre::getStatutsList(),
            'lieux_list' => AOffre::getLieuxList()
        ]);
    }

    #[Route('/new', name: 'app_a_offre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $aOffre = new AOffre();
        $form = $this->createForm(AOffreForm::class, $aOffre);
        
        if ($request->isMethod('POST')) {
        // Convertir la date
            $dateData = $request->request->all()['a_offre_form']['dateLimite'] ?? null;
            if ($dateData) {
                try {
                    // Cette méthode accepte plusieurs formats
                    $dateImmutable = new \DateTimeImmutable($dateData);
                    $aOffre->setDateLimite($dateImmutable);
                } catch (\Exception $e) {
                }
            }
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $aOffre->setSlug($this->generateSlug($aOffre->getTitre()));
            $entityManager->persist($aOffre);
            $entityManager->flush();

            return $this->redirectToRoute('app_a_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/association_manager/a_offre/new.html.twig', [
            'a_offre' => $aOffre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_a_offre_show', methods: ['GET'])]
    public function show(AOffre $aOffre): Response
    {
        return $this->render('admin/association_manager/a_offre/show.html.twig', [
            'a_offre' => $aOffre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_a_offre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AOffre $aOffre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AOffreForm::class, $aOffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $aOffre->setSlug($this->generateSlug($aOffre->getTitre()));
            $entityManager->flush();

            return $this->redirectToRoute('app_a_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/association_manager/a_offre/edit.html.twig', [
            'a_offre' => $aOffre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_a_offre_delete', methods: ['POST'])]
    public function delete(Request $request, AOffre $aOffre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$aOffre->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($aOffre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_a_offre_index', [], Response::HTTP_SEE_OTHER);
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
