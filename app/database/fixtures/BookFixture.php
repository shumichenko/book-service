<?php

declare(strict_types = 1);

namespace App\Fixtures;

use App\Entity\Book;
use Doctrine\Persistence\ObjectManager;

class BookFixture extends BaseFixture
{
    private const NUMBER_OF_RECORDS = 10000;

    public function load(ObjectManager $manager): void
    {
        parent::load($manager);

        $this->createBooks();
    }

    private function createBooks(): void
    {
        for ($i = 1; $i <= self::NUMBER_OF_RECORDS; $i++) {
            $book = new Book($this->faker->word());
            $author = $this->getReference(AuthorFixture::getReferenceById($i));
            $book->addAuthor($author);
            $this->objectManager->persist($book);
        }
        $this->objectManager->flush();
    }
}
