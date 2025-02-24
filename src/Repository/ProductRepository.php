<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }
    // src/Repository/ProductRepository.php

    public function findBySearchQuery(string $query): array
    {
    return $this->createQueryBuilder('p')
        ->where('p.name LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->setMaxResults(10) // Limite les résultats affichés
        ->getQuery()
        ->getResult();
    }

    // src/Repository/ProductRepository.php

    public function findNewProducts(int $limit = 5): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findPopularProducts(int $limit = 5): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.views', 'DESC') // Exemple : tri par nombre de vues
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findBestSellers(int $limit = 5): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.sales', 'DESC') // Exemple : tri par nombre de ventes
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }


//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
