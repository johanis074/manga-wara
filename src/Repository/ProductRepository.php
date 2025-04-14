<?php
namespace App\Repository;

use App\Entity\Product;
use App\Entity\Book;
use App\Entity\Figurine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends ServiceEntityRepository
{
    private BookRepository $bookRepository;
    private FigurineRepository $figurineRepository;

    public function __construct(ManagerRegistry $registry, BookRepository $bookRepository, FigurineRepository $figurineRepository)
    {
        parent::__construct($registry, Product::class);
        $this->bookRepository = $bookRepository;
        $this->figurineRepository = $figurineRepository;
    }

        public function findBySearchQuery(string $query): array
    {
        $books = $this->bookRepository->createQueryBuilder('b')
            ->where('b.name LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        $figurines = $this->figurineRepository->createQueryBuilder('f')
            ->where('f.name LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        return array_merge($books, $figurines);
    }


    public function findPopularProducts(int $limit = 5): array
    {
        return array_merge(
            $this->bookRepository->createQueryBuilder('b')
                ->orderBy('b.views', 'DESC')
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult(),

            $this->figurineRepository->createQueryBuilder('f')
                ->orderBy('f.views', 'DESC')
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult()
        );
    }

    public function findBestSellers(int $limit = 5): array
    {
        return array_merge(
            $this->bookRepository->createQueryBuilder('b')
                ->orderBy('b.sales', 'DESC')
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult(),

            $this->figurineRepository->createQueryBuilder('f')
                ->orderBy('f.sales', 'DESC')
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult()
        );
    }

        // src/Repository/ProductRepository.php
    // src/Repository/ProductRepository.php

        public function findByTypeAndId(string $type, int $id)
        {
            // Si le type est 'book', on interroge la table des livres
            if ($type === 'book') {
                return $this->bookRepository->find($id); // Récupérer un livre par son ID
            }

            // Si le type est 'figurine', on interroge la table des figurines
            if ($type === 'figurine') {
                return $this->figurineRepository->find($id); // Récupérer une figurine par son ID
            }

            // Si le type ne correspond à aucun produit, on retourne null
            return null;
        }


}
