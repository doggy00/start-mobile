<?php

namespace App\State\Books\Providers;

use App\Entity\Books;
use App\State\AbstractDoctrinePaginator;
use App\State\AbstractDoctrinePaginatorProvider;
use App\State\Books\BookPaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

class GetCollection extends AbstractDoctrinePaginatorProvider
{
    protected function getEntityClass(): string
    {
        return Books::class;
    }

    protected function createPaginator(DoctrinePaginator $paginator, int $page, int $itemsPerPage): AbstractDoctrinePaginator
    {
        return new BookPaginator($paginator, $page, $itemsPerPage);
    }
}
