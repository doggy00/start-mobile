<?php

namespace App\State;

use ApiPlatform\Hydra\State\Util\PaginationHelperTrait;
use ApiPlatform\State\Pagination\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Traversable;

abstract class AbstractDoctrinePaginator implements PaginatorInterface, \IteratorAggregate
{
    use PaginationHelperTrait;

    protected DoctrinePaginator $paginator;
    protected int $page;
    protected int $itemsPerPage;

    public function __construct(DoctrinePaginator $paginator, int $page, int $itemsPerPage)
    {
        $this->paginator = $paginator;
        $this->page = $page;
        $this->itemsPerPage = $itemsPerPage;
    }

    public function getIterator(): Traversable
    {
        foreach ($this->paginator as $entity) {
            yield $this->mapEntityToResource($entity);
        }
    }

    abstract protected function mapEntityToResource(object $entity): object;

    public function getTotalItems(): float
    {
        return $this->count();
    }

    public function getCurrentPage(): float
    {
        return $this->page;
    }

    public function getItemsPerPage(): float
    {
        return $this->itemsPerPage;
    }

    public function count(): int
    {
        return count($this->paginator);
    }

    public function getLastPage(): float
    {
        $totalItems = $this->count();
        return $this->itemsPerPage > 0 ? ceil($totalItems / $this->itemsPerPage) : 1;
    }
}
