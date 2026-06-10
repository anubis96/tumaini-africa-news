<?php 
namespace App\Controller\Api;

use App\Entity\AActivity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/association/activities', name: 'api_association_activities_')]
class AActivityApiController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private SerializerInterface $serializer
    ) {}

    #[Route(path:'', name:'list', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $activities = $this->em->getRepository(AActivity::class)->findBy(
            [],
            ['date' => 'DESC']
        );

        // $data = $this->serializer->serialize($activities, 'json', [
        //     'group' => 'api',
        //     'circular_reference_handler' => function ($object){
        //         return $object->getId();
        //     }
        // ]);

        $data = $this->serializer->serialize($activities, 'json', ['group' => 'api']);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $activity = $this->em->getRepository(AActivity::class)->find($id);
        
        if (!$activity) {
            return new JsonResponse([
                'error' => 'Activité non trouvée',
                'message' => "L'activité avec l'ID $id n'existe pas"
            ], Response::HTTP_NOT_FOUND);
        }
        
        $data = $this->serializer->serialize($activity, 'json', ['groups' => 'api']);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/category/{categoryId}', name: 'by_category', methods: ['GET'])]
    public function getByCategory(int $categoryId): JsonResponse
    {
        $activities = $this->em->getRepository(AActivity::class)->findBy(
            ['categories' => $categoryId],
            ['date' => 'DESC']
        );
        
        $data = $this->serializer->serialize($activities, 'json', ['groups' => 'api']);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/status/{status}', name: 'by_status', methods: ['GET'])]
    public function getByStatus(string $status): JsonResponse
    {
        $validStatus = ['planifie', 'en_cours', 'termine'];
        
        if (!in_array($status, $validStatus)) {
            return new JsonResponse([
                'error' => 'Statut invalide',
                'message' => 'Les statuts valides sont: ' . implode(', ', $validStatus)
            ], Response::HTTP_BAD_REQUEST);
        }
        
        $activities = $this->em->getRepository(AActivity::class)->findBy(
            ['status' => $status],
            ['date' => 'DESC']
        );
        
        $data = $this->serializer->serialize($activities, 'json', ['groups' => 'api']);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }
}