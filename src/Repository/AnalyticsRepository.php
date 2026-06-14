<?php
// src/Repository/AnalyticsRepository.php

namespace App\Repository;

use App\Entity\Analytics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AnalyticsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Analytics::class);
    }

    public function getDeviceStats(\DateTimeInterface $startDate = null, \DateTimeInterface $endDate = null): array
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a.deviceType as device, COUNT(a.id) as count')
            ->groupBy('a.deviceType');
        
        if ($startDate) {
            $qb->andWhere('a.visitedAt >= :startDate')->setParameter('startDate', $startDate);
        }
        if ($endDate) {
            $qb->andWhere('a.visitedAt <= :endDate')->setParameter('endDate', $endDate);
        }
        
        return $qb->getQuery()->getResult();
    }

    public function getBrowserStats(\DateTimeInterface $startDate = null, \DateTimeInterface $endDate = null): array
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a.browser as browser, COUNT(a.id) as count')
            ->groupBy('a.browser');
        
        if ($startDate) {
            $qb->andWhere('a.visitedAt >= :startDate')->setParameter('startDate', $startDate);
        }
        if ($endDate) {
            $qb->andWhere('a.visitedAt <= :endDate')->setParameter('endDate', $endDate);
        }
        
        return $qb->getQuery()->getResult();
    }

    public function getOsStats(\DateTimeInterface $startDate = null, \DateTimeInterface $endDate = null): array
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a.os as os, COUNT(a.id) as count')
            ->groupBy('a.os');
        
        if ($startDate) {
            $qb->andWhere('a.visitedAt >= :startDate')->setParameter('startDate', $startDate);
        }
        if ($endDate) {
            $qb->andWhere('a.visitedAt <= :endDate')->setParameter('endDate', $endDate);
        }
        
        return $qb->getQuery()->getResult();
    }

    public function getCountryStats(\DateTimeInterface $startDate = null, \DateTimeInterface $endDate = null): array
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a.country as country, a.countryCode as countryCode, COUNT(a.id) as count')
            ->groupBy('a.country, a.countryCode')
            ->orderBy('count', 'DESC')
            ->setMaxResults(10);
        
        if ($startDate) {
            $qb->andWhere('a.visitedAt >= :startDate')->setParameter('startDate', $startDate);
        }
        if ($endDate) {
            $qb->andWhere('a.visitedAt <= :endDate')->setParameter('endDate', $endDate);
        }
        
        return $qb->getQuery()->getResult();
    }

    public function getPageViewsStats(\DateTimeInterface $startDate = null, \DateTimeInterface $endDate = null): array
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a.pageUrl as page, COUNT(a.id) as views')
            ->groupBy('a.pageUrl')
            ->orderBy('views', 'DESC')
            ->setMaxResults(10);
        
        if ($startDate) {
            $qb->andWhere('a.visitedAt >= :startDate')->setParameter('startDate', $startDate);
        }
        if ($endDate) {
            $qb->andWhere('a.visitedAt <= :endDate')->setParameter('endDate', $endDate);
        }
        
        return $qb->getQuery()->getResult();
    }

    /**
     * Récupère les visites quotidiennes (corrigé pour DQL)
     */
    public function getDailyVisits(\DateTimeInterface $startDate = null, \DateTimeInterface $endDate = null): array
    {
        $conn = $this->getEntityManager()->getConnection();
        
        $sql = "SELECT DATE(a.visited_at) as date, COUNT(a.id) as visits 
                FROM analytics a 
                WHERE 1=1";
        
        $params = [];
        
        if ($startDate) {
            $sql .= " AND a.visited_at >= :startDate";
            $params['startDate'] = $startDate->format('Y-m-d H:i:s');
        }
        if ($endDate) {
            $sql .= " AND a.visited_at <= :endDate";
            $params['endDate'] = $endDate->format('Y-m-d H:i:s');
        }
        
        $sql .= " GROUP BY DATE(a.visited_at) 
                  ORDER BY date ASC";
        
        $stmt = $conn->prepare($sql);
        
        return $stmt->executeQuery($params)->fetchAllAssociative();
    }

    public function getTotalVisits(\DateTimeInterface $startDate = null, \DateTimeInterface $endDate = null): int
    {
        $qb = $this->createQueryBuilder('a')
            ->select('COUNT(a.id)');
        
        if ($startDate) {
            $qb->andWhere('a.visitedAt >= :startDate')->setParameter('startDate', $startDate);
        }
        if ($endDate) {
            $qb->andWhere('a.visitedAt <= :endDate')->setParameter('endDate', $endDate);
        }
        
        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function getUniqueVisitors(\DateTimeInterface $startDate = null, \DateTimeInterface $endDate = null): int
    {
        $qb = $this->createQueryBuilder('a')
            ->select('COUNT(DISTINCT a.sessionId)');
        
        if ($startDate) {
            $qb->andWhere('a.visitedAt >= :startDate')->setParameter('startDate', $startDate);
        }
        if ($endDate) {
            $qb->andWhere('a.visitedAt <= :endDate')->setParameter('endDate', $endDate);
        }
        
        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}