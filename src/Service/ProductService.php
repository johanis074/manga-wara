<?php
namespace App\Service;

use App\Repository\BookRepository;
use App\Repository\FigurineRepository;

class ProductService
{
    private BookRepository $bookRepository;
    private FigurineRepository $figurineRepository;

    public function __construct(BookRepository $bookRepository, FigurineRepository $figurineRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->figurineRepository = $figurineRepository;
    }

    public function findBySearchQuery(string $query): array
    {
        $books = $this->bookRepository->createQueryBuilder('b')
            ->where('b.name LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult();

        $figurines = $this->figurineRepository->createQueryBuilder('f')
            ->where('f.name LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult();

        return array_merge($books, $figurines);
    }

    public function findPopularProducts(int $limit = 6): array
    {
        $books = $this->bookRepository->createQueryBuilder('b')
            ->orderBy('b.views', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        $figurines = $this->figurineRepository->createQueryBuilder('f')
            ->orderBy('f.views', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return array_merge($books, $figurines);
    }

    public function findBestSellers(int $limit = 6): array
    {
        $books = $this->bookRepository->createQueryBuilder('b')
            ->orderBy('b.sales', 'DESC')
            ->getQuery()
            ->getResult();

        $figurines = $this->figurineRepository->createQueryBuilder('f')
            ->orderBy('f.sales', 'DESC')
            ->getQuery()
            ->getResult();

        $all = array_merge($books, $figurines);

        usort($all, fn($a, $b) => $b->getSales() <=> $a->getSales());

        return array_slice($all, 0, $limit);
    }

    public function findByTypeAndId(string $type, int $id): mixed
    {
        return match ($type) {
            'book' => $this->bookRepository->find($id),
            'figurine' => $this->figurineRepository->find($id),
            default => null,
        };
    }

    public function findBonPlanProducts(): array
    {
        $books = $this->bookRepository->createQueryBuilder('b')
            ->where('b.name LIKE :search')
            ->setParameter('search', '%[Bon plan]%')
            ->getQuery()
            ->getResult();

        $figurines = $this->figurineRepository->createQueryBuilder('f')
            ->where('f.name LIKE :search')
            ->setParameter('search', '%[Bon plan]%')
            ->getQuery()
            ->getResult();

        return array_merge($books, $figurines);
    }
}
