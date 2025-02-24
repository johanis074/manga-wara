<?php

namespace App\Repository;

use App\Entity\Figurine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Figurine>
 */
class FigurineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Figurine::class);
    }

        // src/Repository/FigurineRepository.php
    public function findNewFigurines(int $limit = 5): array
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findPopularFigurines(int $limit = 5): array
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.views', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findBestSellingFigurines(int $limit = 5): array
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.sales', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }


    //    /**
    //     * @return Figurine[] Returns an array of Figurine objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Figurine
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
