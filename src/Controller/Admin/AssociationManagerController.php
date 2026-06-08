<?php

namespace App\Controller\Admin;

use App\Entity\AActivity;
use App\Entity\ACategory;
use App\Entity\AMembre;
use App\Entity\AOffre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/association', name: 'app_association_')]
final class AssociationManagerController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(EntityManagerInterface $em): Response
    {
        // Récupérer toutes les catégories pour le compteur
        $categories = $em->getRepository(ACategory::class)->findAll();
        
        // Statistiques Activités
        $activitesTotal = $em->getRepository(AActivity::class)->count([]);
        $activitesEnCours = $em->getRepository(AActivity::class)->count(['status' => 'en_cours']);
        $activitesPlanifiees = $em->getRepository(AActivity::class)->count(['status' => 'planifie']);
        $activitesTerminees = $em->getRepository(AActivity::class)->count(['status' => 'termine']);
        
        // Activités ce mois-ci
        $firstDayOfMonth = new \DateTime('first day of this month');
        $activitesThisMonth = $em->getRepository(AActivity::class)->createQueryBuilder('a')
            ->where('a.date >= :start')
            ->setParameter('start', $firstDayOfMonth)
            ->getQuery()
            ->getResult();
        
        // Statistiques Offres
        $offresActives = $em->getRepository(AOffre::class)->count(['statut' => 'ouvert']);
        $offresExpireBientot = $em->getRepository(AOffre::class)->createQueryBuilder('o')
            ->where('o.dateLimite BETWEEN :now AND :soon')
            ->setParameter('now', new \DateTime())
            ->setParameter('soon', (new \DateTime())->modify('+7 days'))
            ->getQuery()
            ->getResult();
        
        // Statistiques Membres
        $membresTotal = $em->getRepository(AMembre::class)->count([]);
        $currentYear = date('Y');
        $membresNouveaux = $em->getRepository(AMembre::class)->createQueryBuilder('m')
            ->where('m.anciennete = :year')
            ->setParameter('year', $currentYear)
            ->getQuery()
            ->getResult();
        
        // Participants total
        $participantsTotal = $em->getRepository(AActivity::class)->createQueryBuilder('a')
            ->select('SUM(a.participants)')
            ->getQuery()
            ->getSingleScalarResult() ?? 0;
        
        // Dernières activités
        $activitesRecentes = $em->getRepository(AActivity::class)
            ->findBy([], ['date' => 'DESC'], 5);
        
        // Offres récentes
        $offresRecentes = $em->getRepository(AOffre::class)
            ->findBy([], ['dateLimite' => 'DESC'], 5);
        
        $stats = [
            'activites_total' => $activitesTotal,
            'activites_en_cours' => $activitesEnCours,
            'activites_planifiees' => $activitesPlanifiees,
            'activites_terminees' => $activitesTerminees,
            'activites_this_month' => count($activitesThisMonth),
            'offres_actives' => $offresActives,
            'offres_expire_bientot' => count($offresExpireBientot),
            'membres_total' => $membresTotal,
            'membres_nouveaux' => count($membresNouveaux),
            'participants_total' => $participantsTotal,
            'categories_count' => count($categories), // Ajout du compteur de catégories
        ];
        
        return $this->render('admin/association_manager/layout.html.twig', [
            'stats' => $stats,
            'activites_recentes' => $activitesRecentes,
            'offres_recentes' => $offresRecentes,
            'a_categories' => $categories, // Variable pour les catégories
        ]);
    }

    private function getCategoriesCount(EntityManagerInterface $em): int
    {
        return $em->getRepository(ACategory::class)->count([]);
    }
}
