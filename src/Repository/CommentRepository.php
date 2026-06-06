<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

        public function findApprovedByArticle(int $articleId): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.article = :articleId')
            ->andWhere('c.isApproved = true')
            ->orderBy('c.createdAt', 'DESC')
            ->setParameter('articleId', $articleId)
            ->getQuery()
            ->getResult();
    }

    public function findPendingComments(): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.isApproved = false')
            ->andWhere('c.isRejected = false')
            ->orderBy('c.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function countPendingComments(): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.isApproved = false')
            ->andWhere('c.isRejected = false')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countCommentsByArticle(int $articleId): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.article = :articleId')
            ->andWhere('c.isApproved = true')
            ->setParameter('articleId', $articleId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findRecentComments(int $limit = 5): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.isApproved = true')
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
    //    /**
    //     * @return Comment[] Returns an array of Comment objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Comment
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
