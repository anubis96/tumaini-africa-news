<?php 
namespace App\Controller\Api;

use App\Entity\AActivity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
            ['id' => 'DESC']
        );

        $data = [];
        foreach ($activities as $activity) {
            $item = [
                'id' => $activity->getId(),
                'title' => $activity->getTitle(),
                'slug' => $activity->getSlug(),
                'description' => $activity->getDescription(),
                'resume' => $activity->getResume(),
                'date' => $activity->getDate()?->format('Y-m-d'),
                'lieu' => $activity->getLieu(),
                'status' => $activity->getStatus(),
                'imageIcon' => $activity->getImageIcon(),
                'participants' => $activity->getParticipants(),
                'beneficiaires' => $activity->getBeneficiaires(),
                'udatedAt' => $activity->getUdatedAt()?->format('Y-m-d H:i:s'),
                'categories' => $activity->getCategories() ? [
                    'id' => $activity->getCategories()->getId(),
                    'nom' => $activity->getCategories()->getName()
                ] : null,
                // ✅ URL complète de l'image
                'imageUrl' => $this->getPublicUrl($activity->getImageUrl()),
            ];
            $data[] = $item;
        }
        return $this->json($data, Response::HTTP_OK);
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

    #[Route('/slug/{slug}', name: 'show_slug', methods: ['GET'])]
    public function show_slug(string $slug): JsonResponse
    {
        $activity = $this->em->getRepository(AActivity::class)->findOneBy(['slug' => $slug]);
        
        if (!$activity) {
            return new JsonResponse([
                'error' => 'Activité non trouvée',
                'message' => "L'activité avec le slug $slug n'existe pas"
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

        /**
     * Construit l'URL publique complète d'une image
     */
    private function getPublicUrl(?string $imagePath): ?string
    {
        if (empty($imagePath)) {
            return null;
        }
        
        // Si l'URL est déjà complète, la retourner telle quelle
        if (str_starts_with($imagePath, 'http')) {
            return $imagePath;
        }
        
        // Construire l'URL complète
        // Modifiez le port si nécessaire (8000 ou 8001 selon votre serveur)
        return 'https://tumainiafricanews.info/images/activities/' . ltrim($imagePath, '/');
    }
}