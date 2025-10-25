<?php

namespace App\Repository;

use App\Entity\Authors;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Authors>
 */
class AuthorsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Authors::class);
    }

    public function hasAuthors(): bool
    {
        return !empty(
            $this->createQueryBuilder('a')
                ->select('a.id')
                ->getQuery()
                ->getResult()
        );
    }

    public function getAuthorsIds(): array
    {
        return $this->createQueryBuilder('a')
            ->select('a.id')
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }

    public function getBooksCount(Authors $author): int
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(b.id)')
            ->leftJoin('a.books', 'b')
            ->andWhere('a = :author')
            ->setParameter('author', $author)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
