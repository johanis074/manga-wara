<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250610084956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(255) DEFAULT NULL, ADD last_name VARCHAR(255) DEFAULT NULL, ADD address VARCHAR(255) DEFAULT NULL, ADD postal_code VARCHAR(10) DEFAULT NULL, ADD city VARCHAR(100) DEFAULT NULL, DROP billing_address, DROP delivery_address');
    
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD billing_address LONGTEXT DEFAULT NULL, ADD delivery_address LONGTEXT DEFAULT NULL, DROP first_name, DROP last_name, DROP address, DROP postal_code, DROP city');
        $this->addSql('ALTER TABLE comment CHANGE user_id user_id INT DEFAULT NULL');
    }
}
