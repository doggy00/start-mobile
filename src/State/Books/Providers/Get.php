<?php

namespace App\State\Books\Providers;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\v1\Book;
use App\Repository\BooksRepository;

readonly class Get implements ProviderInterface
{
    public function __construct(
        private BooksRepository $booksRepository,
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $entities = $this->booksRepository->find($uriVariables['id'] ?? null);

        if (!$entities) {
            return null;
        }

        $book = new Book();
        $book->id = $entities->getId();
        $book->title = $entities->getTitle();
        $book->author = $entities->getAuthor()?->getName();

        return $book;
    }
}
