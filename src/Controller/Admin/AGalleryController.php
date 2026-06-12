<?php

namespace App\Controller\Admin;

use App\Entity\AGallery;
use App\Entity\GalleryImage;
use App\Form\AGalleryForm;
use App\Repository\AGalleryRepository;
use App\Service\ImageMetadataService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/association/gallery')]
final class AGalleryController extends AbstractController
{
    #[Route(name: 'app_a_gallery_index', methods: ['GET'])]
    public function index(AGalleryRepository $aGalleryRepository): Response
    {
        // $galleries = $em->getRepository(AGallery::class)->findBy([], ['createdAt' => 'DESC']);
        $galleries = $aGalleryRepository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('admin/association_manager/a_gallery/index.html.twig', [
            'galleries' => $galleries,
        ]);
    }

#[Route('/new', name: 'app_a_gallery_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $gallery = new AGallery();
    $form = $this->createForm(AGalleryForm::class, $gallery);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        /** @var UploadedFile[] $imageFiles */
        $imageFiles = $form->get('imageFiles')->getData();
        $imageNames = [];
        
        if ($imageFiles) {
            foreach ($imageFiles as $imageFile) {
                // Générer un nom unique
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                
                // Déplacer le fichier
                $destination = $this->getParameter('kernel.project_dir') . '/public/images/gallery';
                $imageFile->move($destination, $newFilename);
                
                $imageNames[] = $newFilename;
            }
        }
        
        $gallery->setImageNames($imageNames);
        $gallery->setCreatedAt(new \DateTimeImmutable());
        
        $entityManager->persist($gallery);
        $entityManager->flush();
        
        $this->addFlash('success', sprintf('Album "%s" créé avec %d image(s)', $gallery->getTitle(), count($imageNames)));
        return $this->redirectToRoute('app_a_gallery_index');
    }

    return $this->render('admin/association_manager/a_gallery/new.html.twig', [
        'gallery' => $gallery,
        'form' => $form->createView(),
    ]);
}

    #[Route('/{id}', name: 'app_a_gallery_show', methods: ['GET'])]
    public function show(AGallery $aGallery): Response
    {
        return $this->render('admin/association_manager/a_gallery/show.html.twig', [
            'a_gallery' => $aGallery,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_a_gallery_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AGallery $aGallery, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AGalleryForm::class, $aGallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $aGallery->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            return $this->redirectToRoute('app_a_gallery_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/association_manager/a_gallery/edit.html.twig', [
            'a_gallery' => $aGallery,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_a_gallery_delete', methods: ['POST'])]
    public function delete(Request $request, AGallery $aGallery, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$aGallery->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($aGallery);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_a_gallery_index', [], Response::HTTP_SEE_OTHER);
    }
}
