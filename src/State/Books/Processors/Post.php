<?php

namespace App\State\Books\Processors;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\v1\Book;
use App\Entity\Authors;
use App\Entity\Books;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class Post implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Book
    {
        $author = $this->entityManager->getRepository(Authors::class)->findOneBy(['name' => $data->author]);

        if (!$author) {
            throw new NotFoundHttpException('Author not found');
        }

        $entity = new Books();
        $entity->setTitle($data->title);
        $entity->setAuthor($author);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        $book = new Book();
        $book->id = $entity->getId();
        $book->title = $entity->getTitle();
        $book->author = $entity->getAuthor()?->getName();

        return $book;
    }
}
