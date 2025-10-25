<?php

namespace App\State\Books\Processors;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\ORM\EntityManagerInterface;

readonly class Delete implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}
