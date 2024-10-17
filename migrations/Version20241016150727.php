<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241016150727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Check if the table already exists
        $schemaManager = $this->connection->createSchemaManager();
        if (!$schemaManager->tablesExist('author')) {
            $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, manga_id INT NOT NULL, note INT NOT NULL, INDEX IDX_D8892622A76ED395 (user_id), INDEX IDX_D88926227B6461 (manga_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, manga_id INT NOT NULL, content VARCHAR(255) NOT NULL, date DATETIME NOT NULL, likes_count INT DEFAULT NULL, INDEX IDX_794381C6A76ED395 (user_id), INDEX IDX_794381C67B6461 (manga_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
            $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926227B6461 FOREIGN KEY (manga_id) REFERENCES `manga` (id)');
            $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
            $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C67B6461 FOREIGN KEY (manga_id) REFERENCES `manga` (id)');
            $this->addSql('ALTER TABLE manga CHANGE start_date start_date INT DEFAULT NULL');
            $this->addSql('CREATE UNIQUE INDEX unique_manga_author ON manga_author (manga_id, author_id)');
        }

        // Prevent duplicate entries in manga_author table
        $existingEntries = $this->connection->fetchAllAssociative('SELECT manga_id, author_id FROM manga_author');
        $existingEntriesMap = [];
        foreach ($existingEntries as $entry) {
            $existingEntriesMap[$entry['manga_id'] . '-' . $entry['author_id']] = true;
        }

        $newEntries = [
            ['manga_id' => 1, 'author_id' => 1, 'role' => 'story'],
            ['manga_id' => 1, 'author_id' => 2, 'role' => 'art'],
        ];

        foreach ($newEntries as $entry) {
            $key = $entry['manga_id'] . '-' . $entry['author_id'];
            if (!isset($existingEntriesMap[$key])) {
                $this->addSql('INSERT INTO manga_author (manga_id, author_id, role) VALUES (?, ?, ?)', [$entry['manga_id'], $entry['author_id'], $entry['role']]);
            }
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622A76ED395');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D88926227B6461');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C67B6461');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE review');
        $this->addSql('ALTER TABLE `manga` CHANGE start_date start_date INT NOT NULL');
        $this->addSql('DROP INDEX unique_manga_author ON manga_author');
    }
}
