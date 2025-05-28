<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250521130724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders ADD reference VARCHAR(255) NOT NULL, ADD stripe_session_id VARCHAR(255) DEFAULT NULL, ADD total DOUBLE PRECISION NOT NULL, ADD products JSON NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E52FFDEEAEA34913 ON orders (reference)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_E52FFDEEAEA34913 ON orders');
        $this->addSql('ALTER TABLE orders DROP reference, DROP stripe_session_id, DROP total, DROP products');
    }
}
