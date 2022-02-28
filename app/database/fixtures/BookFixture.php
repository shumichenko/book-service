<?php

declare(strict_types = 1);

namespace App\Fixtures;

use App\Entity\Book;
use App\Entity\BookTranslation;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;
use Faker\Generator;

class BookFixture extends BaseFixture
{
    private const NUMBER_OF_RECORDS = 10000;

    private Generator $russianFaker;

    public function load(ObjectManager $manager): void
    {
        parent::load($manager);

        $this->russianFaker = Faker::create('ru_RU');

        $this->createBooks();
    }

    private function createBooks(): void
    {
        for ($i = 1; $i <= self::NUMBER_OF_RECORDS; $i++) {
            $book = new Book();
            $book->addTranslation(new BookTranslation('en', 'name', $this->faker->realText(20)));
            $book->addTranslation(new BookTranslation('ru', 'name', $this->russianFaker->realText(20)));
            $author = $this->getReference(AuthorFixture::getReferenceById($i));
            $book->addAuthor($author);
            $this->objectManager->persist($book);
        }
        $this->objectManager->flush();
    }
}
