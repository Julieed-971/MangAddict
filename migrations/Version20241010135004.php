<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241010135004 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE manga_author (id INT AUTO_INCREMENT NOT NULL, manga_id INT NOT NULL, author_id INT NOT NULL, role VARCHAR(10) NOT NULL, INDEX IDX_6EEF7ACF7B6461 (manga_id), INDEX IDX_6EEF7ACFF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE manga_author ADD CONSTRAINT FK_6EEF7ACF7B6461 FOREIGN KEY (manga_id) REFERENCES `manga` (id)');
        $this->addSql('ALTER TABLE manga_author ADD CONSTRAINT FK_6EEF7ACFF675F31B FOREIGN KEY (author_id) REFERENCES author (id)');
        $this->addSql('ALTER TABLE manga ADD name VARCHAR(255) NOT NULL, ADD genres JSON NOT NULL, DROP title, DROP author, DROP genre, CHANGE volume_number volumes_number INT NOT NULL, CHANGE plot description LONGTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE manga_author DROP FOREIGN KEY FK_6EEF7ACF7B6461');
        $this->addSql('ALTER TABLE manga_author DROP FOREIGN KEY FK_6EEF7ACFF675F31B');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE manga_author');
        $this->addSql('ALTER TABLE `manga` ADD author VARCHAR(255) NOT NULL, ADD genre VARCHAR(255) NOT NULL, DROP genres, CHANGE name title VARCHAR(255) NOT NULL, CHANGE volumes_number volume_number INT NOT NULL, CHANGE description plot LONGTEXT NOT NULL');
    }
}
