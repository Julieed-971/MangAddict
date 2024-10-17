<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241015155611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Check if the table already exists
        $schemaManager = $this->connection->createSchemaManager();
        if (!$schemaManager->tablesExist('manga_author')) {
            // this up() migration is auto-generated, please modify it to your needs
            $this->addSql('CREATE TABLE manga_author (id INT AUTO_INCREMENT NOT NULL, manga_id INT NOT NULL, author_id INT NOT NULL, role VARCHAR(10) NOT NULL, INDEX IDX_6EEF7ACF7B6461 (manga_id), INDEX IDX_6EEF7ACFF675F31B (author_id), UNIQUE INDEX unique_manga_author (manga_id, author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE manga_author ADD CONSTRAINT FK_6EEF7ACF7B6461 FOREIGN KEY (manga_id) REFERENCES `manga` (id)');
            $this->addSql('ALTER TABLE manga_author ADD CONSTRAINT FK_6EEF7ACFF675F31B FOREIGN KEY (author_id) REFERENCES author (id)');
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE manga_author DROP FOREIGN KEY FK_6EEF7ACF7B6461');
        $this->addSql('ALTER TABLE manga_author DROP FOREIGN KEY FK_6EEF7ACFF675F31B');
        $this->addSql('DROP TABLE manga_author');
    }
}
