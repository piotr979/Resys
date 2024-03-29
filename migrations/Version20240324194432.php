<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240324194432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer_entity (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, date_created DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_entity (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, room_entity_id INT NOT NULL, adults INT NOT NULL, status INT NOT NULL, notice VARCHAR(255) DEFAULT NULL, children INT DEFAULT NULL, breakfast TINYINT(1) NOT NULL, date_from DATE NOT NULL, date_to DATE NOT NULL, date_created DATE NOT NULL, price INT NOT NULL, paid TINYINT(1) NOT NULL, INDEX IDX_46D90DF39395C3F3 (customer_id), INDEX IDX_46D90DF31D8C9D34 (room_entity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_entity (id INT AUTO_INCREMENT NOT NULL, bathroom TINYINT(1) NOT NULL, size INT NOT NULL, persons INT NOT NULL, balcony TINYINT(1) NOT NULL, fridge TINYINT(1) NOT NULL, date_created DATE NOT NULL, price_weekday INT NOT NULL, price_weekend INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation_entity ADD CONSTRAINT FK_46D90DF39395C3F3 FOREIGN KEY (customer_id) REFERENCES customer_entity (id)');
        $this->addSql('ALTER TABLE reservation_entity ADD CONSTRAINT FK_46D90DF31D8C9D34 FOREIGN KEY (room_entity_id) REFERENCES room_entity (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation_entity DROP FOREIGN KEY FK_46D90DF39395C3F3');
        $this->addSql('ALTER TABLE reservation_entity DROP FOREIGN KEY FK_46D90DF31D8C9D34');
        $this->addSql('DROP TABLE customer_entity');
        $this->addSql('DROP TABLE reservation_entity');
        $this->addSql('DROP TABLE room_entity');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
