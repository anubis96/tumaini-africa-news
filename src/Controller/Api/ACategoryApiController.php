<?php
// src/Controller/Api/ACategoryApiController.php

namespace App\Controller\Api;

use App\Entity\AActivity;
use App\Entity\ACategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/association/categories', name: 'api_association_categories_')]
class ACategoryApiController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private SerializerInterface $serializer
    ) {}

    #[Route('', name: 'list', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $categories = $this->em->getRepository(ACategory::class)->findBy(
            [],
            ['name' => 'ASC']
        );
        
        $data = $this->serializer->serialize($categories, 'json', ['groups' => 'api']);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $category = $this->em->getRepository(ACategory::class)->find($id);
        
        if (!$category) {
            return new JsonResponse([
                'error' => 'Catégorie non trouvée',
                'message' => "La catégorie avec l'ID $id n'existe pas"
            ], Response::HTTP_NOT_FOUND);
        }
        
        // Ajouter le nombre d'activités pour cette catégorie
        $activitiesCount = $this->em->getRepository(AActivity::class)->count(['categories' => $category]);
        
        $data = $this->serializer->serialize($category, 'json', ['groups' => 'api']);
        $dataArray = json_decode($data, true);
        $dataArray['activities_count'] = $activitiesCount;
        
        return new JsonResponse(json_encode($dataArray), Response::HTTP_OK, [], true);
    }

    #[Route('/slug/{slug}', name: 'by_slug', methods: ['GET'])]
    public function getBySlug(string $slug): JsonResponse
    {
        $category = $this->em->getRepository(ACategory::class)->findOneBy(['slug' => $slug]);
        
        if (!$category) {
            return new JsonResponse([
                'error' => 'Catégorie non trouvée',
                'message' => "La catégorie avec le slug '$slug' n'existe pas"
            ], Response::HTTP_NOT_FOUND);
        }
        
        $data = $this->serializer->serialize($category, 'json', ['groups' => 'api']);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}/activities', name: 'activities', methods: ['GET'])]
    public function getCategoryActivities(int $id): JsonResponse
    {
        $category = $this->em->getRepository(ACategory::class)->find($id);
        
        if (!$category) {
            return new JsonResponse([
                'error' => 'Catégorie non trouvée',
                'message' => "La catégorie avec l'ID $id n'existe pas"
            ], Response::HTTP_NOT_FOUND);
        }
        
        $activities = $this->em->getRepository(AActivity::class)->findBy(
            ['categories' => $category, 'status' => 'en_cours'],
            ['date' => 'DESC']
        );
        
        $data = $this->serializer->serialize($activities, 'json', ['groups' => 'api']);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }
}