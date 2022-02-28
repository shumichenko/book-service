<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220228133531 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Base tables init';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE authors (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE books (id INT UNSIGNED AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE authors_books (book_id INT UNSIGNED NOT NULL, author_id INT UNSIGNED NOT NULL, INDEX IDX_2DFDA3CB16A2B381 (book_id), INDEX IDX_2DFDA3CBF675F31B (author_id), PRIMARY KEY(book_id, author_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE books_translations (id INT AUTO_INCREMENT NOT NULL, object_id INT UNSIGNED DEFAULT NULL, locale VARCHAR(8) NOT NULL, field VARCHAR(32) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX IDX_1E9469D7232D562B (object_id), UNIQUE INDEX lookup_unique_idx (locale, object_id, field), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE authors_books ADD CONSTRAINT FK_2DFDA3CB16A2B381 FOREIGN KEY (book_id) REFERENCES books (id)');
        $this->addSql('ALTER TABLE authors_books ADD CONSTRAINT FK_2DFDA3CBF675F31B FOREIGN KEY (author_id) REFERENCES authors (id)');
        $this->addSql('ALTER TABLE books_translations ADD CONSTRAINT FK_1E9469D7232D562B FOREIGN KEY (object_id) REFERENCES books (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE authors_books DROP FOREIGN KEY FK_2DFDA3CBF675F31B');
        $this->addSql('ALTER TABLE authors_books DROP FOREIGN KEY FK_2DFDA3CB16A2B381');
        $this->addSql('ALTER TABLE books_translations DROP FOREIGN KEY FK_1E9469D7232D562B');
        $this->addSql('DROP TABLE authors');
        $this->addSql('DROP TABLE books');
        $this->addSql('DROP TABLE authors_books');
        $this->addSql('DROP TABLE books_translations');
    }
}
