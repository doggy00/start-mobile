<?php

namespace App\Command;

use App\Entity\Authors;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Random\RandomException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:generate-books',
    description: 'Generate books from database',
)]
class GenerateBooksCommand extends Command
{
    private const int BATCH_SIZE = 500;
    private const int TOTAL_ITEMS = 400000;

    private ?Generator $_faker = null;

    protected ?Generator $faker {
        get => $this->_faker ?? Factory::create();
        set => $this->_faker;
    }

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Connection $connection
    )
    {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $output->writeln("Generating books ...");

        if (!$this->hasAuthors()) {
            $io->error("No authors found. Please enter 'php bin/console doctrine:fixtures:load --group=AuthorFixtures --no-interaction' before generate books");
            return Command::INVALID;
        }

        $this->generateBooks($output);

        $io->success('Done! Books have been generated.');

        return Command::SUCCESS;
    }

    /**
     * @throws RandomException
     */
    private function generateBooks(OutputInterface $output): void
    {
        $rows = [];
        $authorsIds = $this->getAuthorsIds();

        for ($i = 0; $i < self::TOTAL_ITEMS; $i++) {
            $randomAuthorKey = random_int(1, count($authorsIds)) - 1;

            $rows[] = [
                'title' => $this->faker->sentence(3),
                'author_id' => $authorsIds[$randomAuthorKey]['id'],
            ];

            if ($i % self::BATCH_SIZE === 0) {
                $this->insertBooksBatch($rows);
                $rows = [];
                $output->writeln("Inserted $i rows.");
            }
        }

        if ($rows) {
            $this->insertBooksBatch($rows);
        }
    }

    private function insertBooksBatch(array $rows): void
    {
        $sql = 'INSERT INTO books (title, author_id) VALUES ';
        $values = [];
        $params = [];

        foreach ($rows as $row) {
            $values[] = '(?, ?)';
            $params[] = $row['title'];
            $params[] = $row['author_id'];
        }

        $sql .= implode(',', $values);
        $this->connection->executeStatement($sql, $params);
    }

    /**
     * @throws Exception
     */
    private function hasAuthors(): bool
    {
        return $this->entityManager
            ->getRepository(Authors::class)
            ->hasAuthors();
    }

    private function getAuthorsIds(): array
    {
        return $this->entityManager
            ->getRepository(Authors::class)
            ->getAuthorsIds();
    }
}
