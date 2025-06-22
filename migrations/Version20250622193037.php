<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250622193037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book CHANGE editor editor VARCHAR(255) NOT NULL, CHANGE reference reference VARCHAR(100) NOT NULL, CHANGE isbn isbn VARCHAR(13) NOT NULL, CHANGE ean ean VARCHAR(13) NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE figurine CHANGE brand brand VARCHAR(50) NOT NULL, CHANGE reference reference VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book CHANGE reference reference INT NOT NULL, CHANGE isbn isbn INT NOT NULL, CHANGE ean ean INT NOT NULL, CHANGE editor editor VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE figurine CHANGE reference reference INT NOT NULL, CHANGE brand brand VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
    }
}
