<?php

declare(strict_types = 1);

namespace App\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;
use Faker\Generator;

class BaseFixture extends Fixture
{
    protected Generator $faker;
    protected ObjectManager $objectManager;

    public function load(ObjectManager $manager): void
    {
        $this->faker = Faker::create(Faker::DEFAULT_LOCALE);

        $this->objectManager = $manager;
        $this->objectManager->clear();
    }

    /**
     * @template T
     * @return object<T>
     */
    public function getReference($name)
    {
        return parent::getReference($name);
    }
}
