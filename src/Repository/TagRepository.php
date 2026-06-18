<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    /**
     * Récupère les tags les plus utilisés sur une période donnée
     * 
     * @param \DateTimeInterface $startDate Date de début
     * @param int $limit Nombre de tags à récupérer
     * @return Tag[]
     */
    public function findPopularTagsSince(\DateTimeInterface $startDate, int $limit = 12): array
    {
        return $this->createQueryBuilder('t')
            ->select('t, COUNT(a.id) as HIDDEN articleCount')
            ->leftJoin('t.articles', 'a')
            ->where('t.isActive = true')
            ->andWhere('a.isPublished = true')
            ->andWhere('a.publishedAt >= :startDate')
            ->setParameter('startDate', $startDate)
            ->groupBy('t.id')
            ->orderBy('articleCount', 'DESC')
            ->addOrderBy('t.usageCount', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les tags populaires des 3 derniers jours
     */
    public function findPopularTagsLast3Days(int $limit = 12): array
    {
        $startDate = new \DateTimeImmutable('-3 days');
        return $this->findPopularTagsSince($startDate, $limit);
    }

    /**
     * Récupère les tags populaires de la dernière semaine
     */
    public function findPopularTagsLastWeek(int $limit = 12): array
    {
        $startDate = new \DateTimeImmutable('-7 days');
        return $this->findPopularTagsSince($startDate, $limit);
    }

    /**
     * Récupère les tags populaires du dernier mois
     */
    public function findPopularTagsLastMonth(int $limit = 12): array
    {
        $startDate = new \DateTimeImmutable('-30 days');
        return $this->findPopularTagsSince($startDate, $limit);
    }

    /**
     * Récupère les tags les plus utilisés globalement (tous temps)
     */
    public function findMostUsedTags(int $limit = 12): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.isActive = true')
            ->orderBy('t.usageCount', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les tags populaires pour une période donnée
     */
    public function findPopularTagsByPeriod(string $period, int $limit = 12): array
    {
        $startDate = match($period) {
            '3days' => new \DateTimeImmutable('-3 days'),
            'week' => new \DateTimeImmutable('-7 days'),
            'month' => new \DateTimeImmutable('-30 days'),
            'all' => new \DateTimeImmutable('-365 days'), // 1 an
            default => new \DateTimeImmutable('-7 days'),
        };
        
        return $this->findPopularTagsSince($startDate, $limit);
    }

    /**
     * Récupère les tags avec leurs statistiques pour le dashboard
     */
    public function getTagsStats(): array
    {
        $totalTags = $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->where('t.isActive = true')
            ->getQuery()
            ->getSingleScalarResult();

        $totalUsage = $this->createQueryBuilder('t')
            ->select('SUM(t.usageCount)')
            ->where('t.isActive = true')
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'total_tags' => (int) $totalTags,
            'total_usage' => (int) $totalUsage,
            'avg_usage' => $totalTags > 0 ? $totalUsage / $totalTags : 0,
        ];
    }

    /**
     * Récupère les tags par catégorie
     */
    public function findTagsByCategory(string $category, int $limit = 20): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.category = :category')
            ->andWhere('t.isActive = true')
            ->setParameter('category', $category)
            ->orderBy('t.usageCount', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Tag[] Returns an array of Tag objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Tag
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
