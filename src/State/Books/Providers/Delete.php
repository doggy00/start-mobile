<?php

namespace App\State\Books\Providers;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\BooksRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class Delete implements ProviderInterface
{
    public function __construct(
        private BooksRepository $booksRepository,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $book = $this->booksRepository->find($uriVariables['id']);

        if (!$book) {
            throw new NotFoundHttpException('Book not found');
        }

        return $book;
    }
}
