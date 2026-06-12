<?php
// src/Controller/Api/AGalleryApiController.php

namespace App\Controller\Api;

use App\Entity\AGallery;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

#[Route('/api/association/gallery', name: 'api_association_gallery_')]
class AGalleryApiController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private UploaderHelper $uploaderHelper
    ) {}

    #[Route('', name: 'list', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $galleries = $this->em->getRepository(AGallery::class)->findBy(
            [],
            ['createdAt' => 'DESC']
        );
        
        $data = [];
        foreach ($galleries as $gallery) {
            $data[] = $this->formatGallery($gallery);
        }
        
        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $gallery = $this->em->getRepository(AGallery::class)->find($id);
        
        if (!$gallery) {
            return $this->json([
                'error' => 'Galerie non trouvée',
                'message' => "La galerie avec l'ID $id n'existe pas"
            ], Response::HTTP_NOT_FOUND);
        }
        
        return $this->json($this->formatGallery($gallery), Response::HTTP_OK);
    }

    #[Route('/list/latest', name: 'latest', methods: ['GET'])]
    public function getLatest(): JsonResponse
    {
        $galleries = $this->em->getRepository(AGallery::class)->findBy(
            [],
            ['createdAt' => 'DESC'],
            6
        );
        
        $data = [];
        foreach ($galleries as $gallery) {
            $data[] = $this->formatGallery($gallery);
        }
        
        return $this->json($data, Response::HTTP_OK);
    }

    private function formatGallery(AGallery $gallery): array
    {
        // Générer les URLs complètes des images
        $imageUrls = [];
        foreach ($gallery->getImageNames() as $imageName) {
            $imageUrls[] = '/images/gallery/' . $imageName;
        }
        
        return [
            'id' => $gallery->getId(),
            'title' => $gallery->getTitle(),
            'description' => $gallery->getDescription(),
            'image_urls' => $imageUrls,
            'created_at' => $gallery->getCreatedAt()?->format('Y-m-d'),
        ];
    }
}