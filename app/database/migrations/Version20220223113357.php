<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220223113357 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE authors (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE books (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE authors_books (book_id INT UNSIGNED NOT NULL, author_id INT UNSIGNED NOT NULL, INDEX IDX_2DFDA3CB16A2B381 (book_id), INDEX IDX_2DFDA3CBF675F31B (author_id), PRIMARY KEY(book_id, author_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE authors_books ADD CONSTRAINT FK_2DFDA3CB16A2B381 FOREIGN KEY (book_id) REFERENCES books (id)');
        $this->addSql('ALTER TABLE authors_books ADD CONSTRAINT FK_2DFDA3CBF675F31B FOREIGN KEY (author_id) REFERENCES authors (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE authors_books DROP FOREIGN KEY FK_2DFDA3CBF675F31B');
        $this->addSql('ALTER TABLE authors_books DROP FOREIGN KEY FK_2DFDA3CB16A2B381');
        $this->addSql('DROP TABLE authors');
        $this->addSql('DROP TABLE books');
        $this->addSql('DROP TABLE authors_books');
    }
}
