<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    // src/Repository/BookRepository.php
    public function findNewBooks(int $limit = 5): array
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findPopularBooks(int $limit = 5): array
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.views', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

        public function findBestSellingBooks(int $limit = 10): array
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.sales', 'DESC')  // Trier par nombre de ventes (le plus vendu en premier)
            ->setMaxResults($limit)       // Limite le nombre de résultats
            ->getQuery()
            ->getResult();
    }

        public function findByFilters(?string $sort = 'name_asc', ?string $category = null)
    {
        $qb = $this->createQueryBuilder('b');

        // Filtrer par catégorie (si sélectionnée)
        if ($category) {
            $qb->andWhere('b.category = :category')
            ->setParameter('category', $category);
        }

        // Gestion des tris
        switch ($sort) {
            case 'name_desc':
                $qb->orderBy('b.name', 'DESC');
                break;
            case 'price_asc':
                $qb->orderBy('b.price', 'ASC');
                break;
            case 'price_desc':
                $qb->orderBy('b.price', 'DESC');
                break;
            case 'date_new':
                $qb->orderBy('b.createdAt', 'DESC');
                break;
            case 'date_old':
                $qb->orderBy('b.createdAt', 'ASC');
                break;
            case 'popularity':
                $qb->orderBy('b.views', 'DESC');
                break;
            case 'best_sellers':
                $qb->orderBy('b.sales', 'DESC');
                break;
            default: // 'name_asc' par défaut
                $qb->orderBy('b.name', 'ASC');
                break;
        }

        return $qb->getQuery()->getResult();
    }





    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
