<?php

namespace App\State\Books;

use App\ApiResource\v1\Book;
use App\State\AbstractDoctrinePaginator;

class BookPaginator extends AbstractDoctrinePaginator
{
    protected function mapEntityToResource(object $entity): object
    {
        $book = new Book();
        $book->id = $entity->getId();
        $book->title = $entity->getTitle();
        $book->author = $entity->getAuthor()?->getName();

        return $book;
    }
}
