<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241227104058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, locality VARCHAR(255) DEFAULT NULL, street VARCHAR(255) NOT NULL, number VARCHAR(10) NOT NULL, post_code INT NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, lat DOUBLE PRECISION DEFAULT NULL, lon DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, user_organisator_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, recurrence_type VARCHAR(255) NOT NULL, recurrence_end DATETIME DEFAULT NULL, recurrence_count INT DEFAULT NULL, description LONGTEXT NOT NULL, fee NUMERIC(10, 2) NOT NULL, event_type VARCHAR(255) NOT NULL, INDEX IDX_3BAE0AA794589191 (user_organisator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_occurrence (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, INDEX IDX_E61358DC71F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_occurrence_subscription (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, event_occurrence_id INT DEFAULT NULL, subscription_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', occurrence_dates JSON NOT NULL COMMENT \'(DC2Type:json)\', number_participants INT DEFAULT NULL, INDEX IDX_FD585EE9A76ED395 (user_id), INDEX IDX_FD585EE9140E9F00 (event_occurrence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_place (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, gaming_place_id INT DEFAULT NULL, INDEX IDX_3506E2E171F7E88B (event_id), INDEX IDX_3506E2E117EC9A18 (gaming_place_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gaming_place (id INT AUTO_INCREMENT NOT NULL, address_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, place_max INT NOT NULL, UNIQUE INDEX UNIQ_A60B6C3FF5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, address_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, firstname VARCHAR(150) NOT NULL, lastname VARCHAR(200) NOT NULL, phone_number VARCHAR(20) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F5B7AF75 (address_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA794589191 FOREIGN KEY (user_organisator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event_occurrence ADD CONSTRAINT FK_E61358DC71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event_occurrence_subscription ADD CONSTRAINT FK_FD585EE9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event_occurrence_subscription ADD CONSTRAINT FK_FD585EE9140E9F00 FOREIGN KEY (event_occurrence_id) REFERENCES event_occurrence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_place ADD CONSTRAINT FK_3506E2E171F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE event_place ADD CONSTRAINT FK_3506E2E117EC9A18 FOREIGN KEY (gaming_place_id) REFERENCES gaming_place (id)');
        $this->addSql('ALTER TABLE gaming_place ADD CONSTRAINT FK_A60B6C3FF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA794589191');
        $this->addSql('ALTER TABLE event_occurrence DROP FOREIGN KEY FK_E61358DC71F7E88B');
        $this->addSql('ALTER TABLE event_occurrence_subscription DROP FOREIGN KEY FK_FD585EE9A76ED395');
        $this->addSql('ALTER TABLE event_occurrence_subscription DROP FOREIGN KEY FK_FD585EE9140E9F00');
        $this->addSql('ALTER TABLE event_place DROP FOREIGN KEY FK_3506E2E171F7E88B');
        $this->addSql('ALTER TABLE event_place DROP FOREIGN KEY FK_3506E2E117EC9A18');
        $this->addSql('ALTER TABLE gaming_place DROP FOREIGN KEY FK_A60B6C3FF5B7AF75');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F5B7AF75');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_occurrence');
        $this->addSql('DROP TABLE event_occurrence_subscription');
        $this->addSql('DROP TABLE event_place');
        $this->addSql('DROP TABLE gaming_place');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
