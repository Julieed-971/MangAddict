<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241015160016 extends AbstractMigration
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
            $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX unique_author_name (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE `manga` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image_url VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, start_date INT DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, volumes_number INT DEFAULT NULL, genres JSON NOT NULL, description LONGTEXT NOT NULL, UNIQUE INDEX unique_author_name (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE manga_author (id INT AUTO_INCREMENT NOT NULL, manga_id INT NOT NULL, author_id INT NOT NULL, role VARCHAR(10) NOT NULL, INDEX IDX_6EEF7ACF7B6461 (manga_id), INDEX IDX_6EEF7ACFF675F31B (author_id), UNIQUE INDEX unique_manga_author (manga_id, author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) DEFAULT NULL, firstname VARCHAR(180) NOT NULL, lastname VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, last_connected_at DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE manga_author ADD CONSTRAINT FK_6EEF7ACF7B6461 FOREIGN KEY (manga_id) REFERENCES `manga` (id)');
            $this->addSql('ALTER TABLE manga_author ADD CONSTRAINT FK_6EEF7ACFF675F31B FOREIGN KEY (author_id) REFERENCES author (id)');
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE manga_author DROP FOREIGN KEY FK_6EEF7ACF7B6461');
        $this->addSql('ALTER TABLE manga_author DROP FOREIGN KEY FK_6EEF7ACFF675F31B');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE `manga`');
        $this->addSql('DROP TABLE manga_author');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
