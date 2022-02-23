<?php

declare(strict_types = 1);

namespace App\Fixtures;

use App\Entity\Author;
use Doctrine\Persistence\ObjectManager;
use DomainException;
use function sprintf;

class AuthorFixture extends BaseFixture
{
    private const REFERENCE_BASE_NAME = 'author_number_%d';
    private const NUMBER_OF_RECORDS = 10000;

    public function load(ObjectManager $manager): void
    {
        parent::load($manager);

        $this->createAuthors();
    }

    private function createAuthors(): void
    {
        for ($i = 1; $i <= self::NUMBER_OF_RECORDS; $i++) {
            $authorName = sprintf('%s %s', $this->faker->firstName(), $this->faker->lastName);
            $author = new Author($authorName);
            $this->addReference(self::getReferenceById($i), $author);
            $this->objectManager->persist($author);
        }
        $this->objectManager->flush();
    }

    public static function getReferenceById(int $id): string
    {
        if ($id > self::NUMBER_OF_RECORDS) {
            throw new DomainException('Such reference does not exist');
        }

        return sprintf(self::REFERENCE_BASE_NAME, $id);
    }
}
