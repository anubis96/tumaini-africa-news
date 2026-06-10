<?php
// src/Controller/Api/AOffreApiController.php

namespace App\Controller\Api;

use App\Entity\AOffre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/association/offers', name: 'api_association_offers_')]
class AOffreApiController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private SerializerInterface $serializer
    ) {}

    #[Route('', name: 'list', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $offers = $this->em->getRepository(AOffre::class)->findBy(
            [],
            ['dateLimite' => 'ASC']
        );
        
        $data = $this->serializer->serialize($offers, 'json', ['groups' => 'api']);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $offer = $this->em->getRepository(AOffre::class)->find($id);
        
        if (!$offer) {
            return new JsonResponse([
                'error' => 'Offre non trouvée',
                'message' => "L'offre avec l'ID $id n'existe pas"
            ], Response::HTTP_NOT_FOUND);
        }
        
        $data = $this->serializer->serialize($offer, 'json', ['groups' => 'api']);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/statut/{statut}', name: 'by_statut', methods: ['GET'])]
    public function getByStatut(string $statut): JsonResponse
    {
        $validStatuts = ['ouvert', 'ferme', 'en_attente', 'pourvu'];
        
        if (!in_array($statut, $validStatuts)) {
            return new JsonResponse([
                'error' => 'Statut invalide',
                'message' => 'Les statuts valides sont: ' . implode(', ', $validStatuts)
            ], Response::HTTP_BAD_REQUEST);
        }
        
        $offers = $this->em->getRepository(AOffre::class)->findBy(
            ['statut' => $statut],
            ['dateLimite' => 'ASC']
        );
        
        $data = $this->serializer->serialize($offers, 'json', ['groups' => 'api']);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/type/{type}', name: 'by_type', methods: ['GET'])]
    public function getByType(string $type): JsonResponse
    {
        $validTypes = ['cdi', 'cdd', 'stage', 'consultant', 'temps_plein', 'temps_partiel', 'freelance'];
        
        if (!in_array($type, $validTypes)) {
            return new JsonResponse([
                'error' => 'Type invalide',
                'message' => 'Types valides: ' . implode(', ', $validTypes)
            ], Response::HTTP_BAD_REQUEST);
        }
        
        $offers = $this->em->getRepository(AOffre::class)->findBy(
            ['type' => $type],
            ['dateLimite' => 'ASC']
        );
        
        $data = $this->serializer->serialize($offers, 'json', ['groups' => 'api']);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }
}