<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250630093206 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change price column from VARCHAR to DECIMAL(10,2) in product table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            ALTER TABLE product
            ALTER COLUMN price TYPE DECIMAL(10, 2)
            USING price::DECIMAL(10, 2)
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("
            ALTER TABLE product
            ALTER COLUMN price TYPE VARCHAR(255)
            USING price::TEXT
        ");
    }
}
