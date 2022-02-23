<?php

declare(strict_types = 1);

namespace App\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class BaseFixture extends Fixture
{
    protected Generator $faker;
    protected ObjectManager $objectManager;

    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();
        $this->objectManager = $manager;
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
