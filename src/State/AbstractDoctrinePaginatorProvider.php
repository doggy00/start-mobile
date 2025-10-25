<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

abstract class AbstractDoctrinePaginatorProvider implements ProviderInterface
{
    public function __construct(
        protected EntityManagerInterface $em
    ) {}

    abstract protected function getEntityClass(): string;
    abstract protected function createPaginator(DoctrinePaginator $paginator, int $page, int $itemsPerPage): AbstractDoctrinePaginator;

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array|null|object
    {
        $page = max(1, (int)($context['filters']['page'] ?? 1));
        $itemsPerPage = (int)($context['filters']['itemsPerPage'] ?? 20);
        $offset = ($page - 1) * $itemsPerPage;

        $qb = $this->em->createQueryBuilder()
            ->select('e')
            ->from($this->getEntityClass(), 'e')
            ->orderBy('e.id', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($itemsPerPage);

        $paginator = new DoctrinePaginator($qb, true);

        return $this->createPaginator($paginator, $page, $itemsPerPage);
    }
}
