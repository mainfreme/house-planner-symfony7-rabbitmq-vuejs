<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250331194438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE history (id SERIAL NOT NULL, class VARCHAR(255) NOT NULL, action VARCHAR(255) NOT NULL, datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, user_id INT NOT NULL, modify_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN history.modify_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE image (id SERIAL NOT NULL, data TEXT NOT NULL, property JSON NOT NULL, is_delete BOOLEAN NOT NULL, is_active BOOLEAN DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE product_images (id SERIAL NOT NULL, image_id INT DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8263FFCE3DA5256D ON product_images (image_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_images ADD CONSTRAINT FK_8263FFCE3DA5256D FOREIGN KEY (image_id) REFERENCES product_images (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_images DROP CONSTRAINT FK_8263FFCE3DA5256D
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE history
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE image
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE product_images
        SQL);
    }
}
