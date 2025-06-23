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
            ->orderBy('f.sales', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findByFilters(?string $sort = 'name_asc', ?string $brand = null)
    {
        $qb = $this->createQueryBuilder('f');

        if ($brand) {
            $qb->andWhere('f.brand = :brand')
               ->setParameter('brand', $brand);
        }

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
            default:
                $qb->orderBy('f.name', 'ASC');
                break;
        }

        return $qb->getQuery()->getResult();
    }

    public function findByTitleAndBrandExcludingId(string $title, string $brand, int $excludedId): array
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

    public function findDistinctBrands(): array
    {
        return $this->createQueryBuilder('f')
            ->select('DISTINCT f.brand')
            ->orderBy('f.brand', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();
    }
}
