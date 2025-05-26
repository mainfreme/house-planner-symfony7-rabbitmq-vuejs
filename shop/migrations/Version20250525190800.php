<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250525190800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE client (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, nip VARCHAR(255) NOT NULL, regon VARCHAR(255) DEFAULT NULL, pesel VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, number_phone INT DEFAULT NULL, country VARCHAR(255) NOT NULL, phone_prefix VARCHAR(5) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE client_address (id SERIAL NOT NULL, client_id INT NOT NULL, street VARCHAR(255) NOT NULL, postal_code VARCHAR(20) NOT NULL, city VARCHAR(100) NOT NULL, state_province VARCHAR(100) NOT NULL, country VARCHAR(100) NOT NULL, additional_info TEXT NOT NULL, house_number VARCHAR(10) NOT NULL, apartment_number VARCHAR(15) NOT NULL, is_primary BOOLEAN NOT NULL, added_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5F732BFC19EB6921 ON client_address (client_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN client_address.added_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE file (id SERIAL NOT NULL, product_id INT NOT NULL, data TEXT NOT NULL, property JSON NOT NULL, is_delete BOOLEAN NOT NULL, is_active BOOLEAN DEFAULT NULL, uuid UUID NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8C9F36104584665A ON file (product_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN file.uuid IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE history (id SERIAL NOT NULL, class VARCHAR(255) NOT NULL, action VARCHAR(255) NOT NULL, datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, user_id INT NOT NULL, modify_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN history.modify_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE image (id SERIAL NOT NULL, product_id INT NOT NULL, data TEXT NOT NULL, property JSON NOT NULL, is_delete BOOLEAN NOT NULL, is_active BOOLEAN DEFAULT NULL, uuid UUID NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C53D045F4584665A ON image (product_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN image.uuid IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE product (id SERIAL NOT NULL, type_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, price VARCHAR(255) NOT NULL, is_active BOOLEAN NOT NULL, parameters JSON NOT NULL, uuid UUID NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_D34A04ADD17F50A6 ON product (uuid)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D34A04ADC54C8C93 ON product (type_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN product.uuid IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE product_type (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, is_public BOOLEAN NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE template (id SERIAL NOT NULL, file_id INT NOT NULL, title VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, number TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_97601F8393CB796C ON template (file_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN template.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE template_key (id SERIAL NOT NULL, template_id INT NOT NULL, key VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_24FB228B5DA0FB8 ON template_key (template_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE client_address ADD CONSTRAINT FK_5F732BFC19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE file ADD CONSTRAINT FK_8C9F36104584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image ADD CONSTRAINT FK_C53D045F4584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product ADD CONSTRAINT FK_D34A04ADC54C8C93 FOREIGN KEY (type_id) REFERENCES product_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE template ADD CONSTRAINT FK_97601F8393CB796C FOREIGN KEY (file_id) REFERENCES file (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE template_key ADD CONSTRAINT FK_24FB228B5DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE client_address DROP CONSTRAINT FK_5F732BFC19EB6921
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE file DROP CONSTRAINT FK_8C9F36104584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image DROP CONSTRAINT FK_C53D045F4584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product DROP CONSTRAINT FK_D34A04ADC54C8C93
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE template DROP CONSTRAINT FK_97601F8393CB796C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE template_key DROP CONSTRAINT FK_24FB228B5DA0FB8
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE client
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE client_address
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE file
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE history
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE image
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE product
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE product_type
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE template
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE template_key
        SQL);
    }
}
