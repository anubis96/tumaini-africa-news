<?php
// src/Controller/Api/AssociationApiController.php

namespace App\Controller\Api;

use App\Entity\AActivity;
use App\Entity\AOffre;
use App\Entity\AMembre;
use App\Entity\ACategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/association', name: 'api_association_')]
class AssociationApiController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private SerializerInterface $serializer
    ) {}

    #[Route('/dashboard', name: 'dashboard', methods: ['GET'])]
    public function getDashboardStats(): JsonResponse
    {
        $activitiesCount = $this->em->getRepository(AActivity::class)->count(['status' => 'en_cours']);
        $offersCount = $this->em->getRepository(AOffre::class)->count(['statut' => 'ouvert']);
        $membersCount = $this->em->getRepository(AMembre::class)->count([]);
        $categoriesCount = $this->em->getRepository(ACategory::class)->count([]);
        
        // Dernières activités
        $recentActivities = $this->em->getRepository(AActivity::class)->findBy(
            ['status' => 'en_cours'],
            ['date' => 'DESC'],
            5
        );
        
        // Dernières offres
        $recentOffers = $this->em->getRepository(AOffre::class)->findBy(
            ['statut' => 'ouvert'],
            ['dateLimite' => 'ASC'],
            5
        );
        
        $stats = [
            'total_activities' => $activitiesCount,
            'total_offers' => $offersCount,
            'total_members' => $membersCount,
            'total_categories' => $categoriesCount,
            'recent_activities' => $recentActivities,
            'recent_offers' => $recentOffers
        ];
        
        $data = $this->serializer->serialize($stats, 'json', ['groups' => 'api']);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/search', name: 'search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        $query = $request->query->get('q', '');
        $type = $request->query->get('type', 'all');
        
        $results = [];
        
        if ($type === 'all' || $type === 'activities') {
            $activities = $this->em->getRepository(AActivity::class)->createQueryBuilder('a')
                ->where('a.titre LIKE :query')
                ->orWhere('a.description LIKE :query')
                ->setParameter('query', '%' . $query . '%')
                ->setMaxResults(5)
                ->getQuery()
                ->getResult();
            $results['activities'] = $activities;
        }
        
        if ($type === 'all' || $type === 'offers') {
            $offers = $this->em->getRepository(AOffre::class)->createQueryBuilder('o')
                ->where('o.titre LIKE :query')
                ->orWhere('o.description LIKE :query')
                ->setParameter('query', '%' . $query . '%')
                ->setMaxResults(5)
                ->getQuery()
                ->getResult();
            $results['offers'] = $offers;
        }
        
        if ($type === 'all' || $type === 'members') {
            $members = $this->em->getRepository(AMembre::class)->createQueryBuilder('m')
                ->where('m.nom LIKE :query')
                ->orWhere('m.poste LIKE :query')
                ->orWhere('m.specialite LIKE :query')
                ->setParameter('query', '%' . $query . '%')
                ->setMaxResults(5)
                ->getQuery()
                ->getResult();
            $results['members'] = $members;
        }
        
        $data = $this->serializer->serialize($results, 'json', ['groups' => 'api']);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }
}