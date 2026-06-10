<?php
// src/Controller/Api/AMembreApiController.php

namespace App\Controller\Api;

use App\Entity\AMembre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/association/members', name: 'api_association_members_')]
class AMembreApiController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private SerializerInterface $serializer
    ) {}

    #[Route('', name: 'list', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $members = $this->em->getRepository(AMembre::class)->findBy(
            [],
            ['nom' => 'ASC']
        );
        
        $data = $this->serializer->serialize($members, 'json', ['groups' => 'api']);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $member = $this->em->getRepository(AMembre::class)->find($id);
        
        if (!$member) {
            return new JsonResponse([
                'error' => 'Membre non trouvé',
                'message' => "Le membre avec l'ID $id n'existe pas"
            ], Response::HTTP_NOT_FOUND);
        }
        
        $data = $this->serializer->serialize($member, 'json', ['groups' => 'api']);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/poste/{poste}', name: 'by_poste', methods: ['GET'])]
    public function getByPoste(string $poste): JsonResponse
    {
        $members = $this->em->getRepository(AMembre::class)->createQueryBuilder('m')
            ->where('m.poste LIKE :poste')
            ->setParameter('poste', '%' . $poste . '%')
            ->orderBy('m.nom', 'ASC')
            ->getQuery()
            ->getResult();
        
        $data = $this->serializer->serialize($members, 'json', ['groups' => 'api']);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/specialite/{specialite}', name: 'by_specialite', methods: ['GET'])]
    public function getBySpecialite(string $specialite): JsonResponse
    {
        $members = $this->em->getRepository(AMembre::class)->createQueryBuilder('m')
            ->where('m.specialite LIKE :specialite')
            ->setParameter('specialite', '%' . $specialite . '%')
            ->orderBy('m.nom', 'ASC')
            ->getQuery()
            ->getResult();
        
        $data = $this->serializer->serialize($members, 'json', ['groups' => 'api']);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }
}