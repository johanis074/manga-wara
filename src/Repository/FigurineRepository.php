<?php

namespace App\Repository;

use App\Entity\Figurine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Enum\Brand;

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

    public function findBestSellingFigurines(int $limit = 10): array
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.sales', 'DESC')  // Trier par nombre de ventes (le plus vendu en premier)
            ->setMaxResults($limit)       // Limite le nombre de résultats
            ->getQuery()
            ->getResult();
    }

    public function findByFilters(?string $sort = 'name_asc', ?string $category = null)
    {
        $qb = $this->createQueryBuilder('f');

        // Filtrer par catégorie (si sélectionnée)
        if ($category) {
            $qb->andWhere('f.category = :category')
               ->setParameter('category', $category);
        }

        // Gestion des tris
        switch ($sort) {
            case 'name_desc':
                $qb->orderBy('f.name', 'DESC');
                break;
            case 'price_asc':
                $qb->orderBy('f.price', 'ASC');
                break;
            case 'price_desc':
                $qb->orderBy('f.price', 'DESC');
                break;
            case 'date_new':
                $qb->orderBy('f.createdAt', 'DESC');
                break;
            case 'date_old':
                $qb->orderBy('f.createdAt', 'ASC');
                break;
            case 'popularity':
                $qb->orderBy('f.views', 'DESC');
                break;
            case 'best_sellers':
                $qb->orderBy('f.sales', 'DESC');
                break;
            default: // 'name_asc' par défaut
                $qb->orderBy('f.name', 'ASC');
                break;
        }

        return $qb->getQuery()->getResult();
    }

    public function findByTitleAndBrandExcludingId(string $title, Brand $brand, int $excludedId): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.name = :title')
            ->andWhere('f.brand = :brand')
            ->andWhere('f.id != :excludedId')
            ->setParameter('title', $title)
            ->setParameter('brand', $brand)
            ->setParameter('excludedId', $excludedId)
            ->getQuery()
            ->getResult();
    }

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

