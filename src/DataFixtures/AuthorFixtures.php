<?php

namespace App\DataFixtures;

use App\Entity\Authors;
use App\Entity\Books;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AuthorFixtures extends Fixture
{
    private const int TOTAL_AUTHORS = 3;

    private ?Generator $_faker = null;

    protected ?Generator $faker {
        get => $this->_faker ?? Factory::create();
        set => $this->_faker;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::TOTAL_AUTHORS; $i++) {
            $author = new Authors();
            $author->setName($this->faker->name());

            $manager->persist($author);
        }

        $manager->flush();
        $manager->clear();
    }
}
