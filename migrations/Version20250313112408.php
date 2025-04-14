<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250313112408 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment ADD figurine_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CC550FC1B FOREIGN KEY (figurine_id) REFERENCES figurine (id)');
        $this->addSql('CREATE INDEX IDX_9474526CC550FC1B ON comment (figurine_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CC550FC1B');
        $this->addSql('DROP INDEX IDX_9474526CC550FC1B ON comment');
        $this->addSql('ALTER TABLE comment DROP figurine_id');
    }
}
