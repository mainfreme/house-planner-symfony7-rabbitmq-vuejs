<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Consolidated migration: Creates all tables with UUID primary keys
 * Combines all previous migrations into one comprehensive migration
 */
final class Version20251129091636 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create all database tables with UUID primary keys (consolidated from all previous migrations)';
    }

    public function up(Schema $schema): void
    {
        // ============================================================================
        // TABLES FROM: Version20250525190800.php
        // ============================================================================

        // Table: client
        // Source: Version20250525190800.php
        $this->addSql(<<<'SQL'
            CREATE TABLE client (
                uuid UUID NOT NULL,
                address_uuid UUID NULL,
                name VARCHAR(255) NOT NULL,
                nip VARCHAR(255) NOT NULL,
                regon VARCHAR(255) DEFAULT NULL,
                pesel VARCHAR(255) DEFAULT NULL,
                email VARCHAR(255) DEFAULT NULL,
                number_phone varchar(15) DEFAULT NULL,
                country VARCHAR(255) NOT NULL,
                phone_prefix VARCHAR(5) NOT NULL,
                PRIMARY KEY(uuid)
            )
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN client.uuid IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IF NOT EXISTS IDX_5F7213BFC19EB6918 ON client (address_uuid)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN client.address_uuid IS '(DC2Type:uuid)'
        SQL);

        // Table: client_address
        // Source: Version20250525190800.php
        // Modified: client_uuid changed from INT to UUID
        $this->addSql(<<<'SQL'
            CREATE TABLE client_address (
                uuid UUID NOT NULL,
                client_uuid UUID NOT NULL,
                street VARCHAR(255) NOT NULL,
                postal_code VARCHAR(20) NOT NULL,
                city VARCHAR(100) NOT NULL,
                state_province VARCHAR(100) NOT NULL,
                country VARCHAR(100) NOT NULL,
                additional_info TEXT NOT NULL,
                house_number VARCHAR(10) NOT NULL,
                apartment_number VARCHAR(15) NOT NULL,
                is_primary BOOLEAN NOT NULL,
                added_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                PRIMARY KEY(uuid)
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IF NOT EXISTS IDX_5F732BFC19EB6921 ON client_address (client_uuid)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN client_address.uuid IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN client_address.client_uuid IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN client_address.added_at IS '(DC2Type:datetime_immutable)'
        SQL);

        $this->addSql(<<<'SQL'
            ALTER TABLE client_address ADD COLUMN IF NOT EXISTS client_uuid UUID NOT NULL
        SQL);

        // Table: product_type
        // Source: Version20250525190800.php
        $this->addSql(<<<'SQL'
            CREATE TABLE product_type (
                uuid UUID NOT NULL,
                name VARCHAR(255) NOT NULL,
                link VARCHAR(255) NOT NULL,
                is_public BOOLEAN NOT NULL,
                PRIMARY KEY(uuid)
            )
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN product_type.uuid IS '(DC2Type:uuid)'
        SQL);

        // Table: product
        // Source: Version20250525190800.php
        // Modified: uuid and type_uuid changed from INT/SERIAL to UUID, price changed to DECIMAL (from Version20250630093206.php)
        $this->addSql(<<<'SQL'
            CREATE TABLE product (
                uuid UUID NOT NULL,
                type_uuid UUID NOT NULL,
                name VARCHAR(255) NOT NULL,
                description TEXT DEFAULT NULL,
                price DECIMAL(10, 2) NOT NULL,
                is_active BOOLEAN NOT NULL,
                parameters JSON NOT NULL,
                legacy_uuid UUID NOT NULL,
                PRIMARY KEY(uuid)
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX IF NOT EXISTS UNIQ_D34A04ADD17F50A6 ON product (legacy_uuid)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IF NOT EXISTS IDX_D34A04ADC54C8C93 ON product (type_uuid)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN product.uuid IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN product.type_uuid IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN product.legacy_uuid IS '(DC2Type:uuid)'
        SQL);

        // Table: file
        // Source: Version20250525190800.php
        // Modified: uuid and product_uuid changed from INT/SERIAL to UUID
        // Additional columns from: Version20250615123148.php (file_name, file_type, related_id)
        $this->addSql(<<<'SQL'
            CREATE TABLE file (
                uuid UUID NOT NULL,
                product_uuid UUID NOT NULL,
                data TEXT NOT NULL,
                property JSON NOT NULL,
                is_delete BOOLEAN NOT NULL,
                is_active BOOLEAN DEFAULT NULL,
                legacy_uuid UUID NOT NULL,
                file_name VARCHAR(255) NOT NULL,
                file_type VARCHAR(255) NOT NULL,
                related_id INT DEFAULT NULL,
                PRIMARY KEY(uuid)
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IF NOT EXISTS IDX_8C9F36104584665A ON file (product_uuid)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN file.uuid IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN file.product_uuid IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN file.legacy_uuid IS '(DC2Type:uuid)'
        SQL);

        // Table: image
        // Source: Version20250525190800.php
        // Modified: uuid and product_uuid changed from INT/SERIAL to UUID
        // Additional column from: Version20250526135611.php (is_main)
        $this->addSql(<<<'SQL'
            CREATE TABLE image (
                uuid UUID NOT NULL,
                product_uuid UUID NOT NULL,
                data TEXT NOT NULL,
                property JSON NOT NULL,
                is_delete BOOLEAN NOT NULL,
                is_active BOOLEAN DEFAULT NULL,
                legacy_uuid UUID NOT NULL,
                is_main BOOLEAN DEFAULT NULL,
                PRIMARY KEY(uuid)
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IF NOT EXISTS IDX_C53D045F4584665A ON image (product_uuid)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN image.uuid IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN image.product_uuid IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN image.legacy_uuid IS '(DC2Type:uuid)'
        SQL);

        // Table: history
        // Source: Version20250525190800.php
        // Modified: uuid changed from SERIAL to UUID, user_id remains INT (no user table relationship defined in original)
        $this->addSql(<<<'SQL'
            CREATE TABLE history (
                uuid UUID NOT NULL,
                class VARCHAR(255) NOT NULL,
                action VARCHAR(255) NOT NULL,
                datetime TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                user_id INT NOT NULL,
                modify_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                PRIMARY KEY(uuid)
            )
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN history.uuid IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN history.modify_at IS '(DC2Type:datetime_immutable)'
        SQL);

        // Table: template
        // Source: Version20250525190800.php
        // Modified: uuid and file_uuid changed from INT/SERIAL to UUID
        $this->addSql(<<<'SQL'
            CREATE TABLE template (
                uuid UUID NOT NULL,
                file_uuid UUID NOT NULL,
                title VARCHAR(255) NOT NULL,
                type VARCHAR(255) NOT NULL,
                number TEXT NOT NULL,
                created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                PRIMARY KEY(uuid)
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX IF NOT EXISTS UNIQ_97601F8393CB796C ON template (file_uuid)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN template.uuid IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN template.file_uuid IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN template.created_at IS '(DC2Type:datetime_immutable)'
        SQL);

        // Table: template_key
        // Source: Version20250525190800.php
        // Modified: uuid and template_uuid changed from INT/SERIAL to UUID
        $this->addSql(<<<'SQL'
            CREATE TABLE template_key (
                uuid UUID NOT NULL,
                template_uuid UUID NOT NULL,
                key VARCHAR(255) NOT NULL,
                PRIMARY KEY(uuid)
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX IF NOT EXISTS UNIQ_24FB228B5DA0FB8 ON template_key (template_uuid)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN template_key.uuid IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN template_key.template_uuid IS '(DC2Type:uuid)'
        SQL);

        // ============================================================================
        // TABLES FROM: Version20250615123148.php
        // ============================================================================

        // Table: product_images
        // Source: Version20250615123148.php
        // Modified: uuid and image_uuid changed from INT/SERIAL to UUID
        $this->addSql(<<<'SQL'
            CREATE TABLE product_images (
                uuid UUID NOT NULL,
                image_uuid UUID DEFAULT NULL,
                PRIMARY KEY(uuid)
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IF NOT EXISTS IDX_8263FFCE3DA5256D ON product_images (image_uuid)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN product_images.uuid IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN product_images.image_uuid IS '(DC2Type:uuid)'
        SQL);

        // Table: user
        // Source: Version20250615123148.php
        // Modified: uuid changed from SERIAL to UUID
        // Note: Index name changed in Version20250726193357.php (UNIQ_8D93D649E7927C74 -> UNIQ_1483A5E9E7927C74)
        $this->addSql(<<<'SQL'
            CREATE TABLE "user" (
                uuid UUID NOT NULL,
                email VARCHAR(180) NOT NULL,
                roles JSON NOT NULL,
                password VARCHAR(255) NOT NULL,
                PRIMARY KEY(uuid)
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX IF NOT EXISTS UNIQ_1483A5E9E7927C74 ON "user" (email)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN "user".uuid IS '(DC2Type:uuid)'
        SQL);

        // ============================================================================
        // TABLES FROM: Version20250726193357.php
        // ============================================================================

        // Table: contact
        // Source: Version20250726193357.php
        // Modified: uuid changed from SERIAL to UUID
        $this->addSql(<<<'SQL'
            CREATE TABLE contact (
                uuid UUID NOT NULL,
                name VARCHAR(255) NOT NULL,
                surname VARCHAR(255) NOT NULL,
                email VARCHAR(100) NOT NULL,
                phone_number varchar(15) DEFAULT NULL,
                country VARCHAR(100) NOT NULL,
                language VARCHAR(100) NOT NULL,
                area_code VARCHAR(10) NOT NULL,
                note TEXT NOT NULL,
                added_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                PRIMARY KEY(uuid)
            )
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN contact.uuid IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN contact.added_at IS '(DC2Type:datetime_immutable)'
        SQL);

        // ============================================================================
        // TABLE: company_accounts
        // ============================================================================


        $this->addSql(<<<'SQL'
            CREATE TABLE company_accounts (
                uuid UUID NOT NULL,
                address_uuid UUID NOT NULL,
                client_uuid UUID NOT NULL,
                name VARCHAR(255) NOT NULL,
                number VARCHAR(255) NOT NULL,
                swift_code VARCHAR(255) NOT NULL,
                iban VARCHAR(255) NOT NULL,
                bic VARCHAR(255) NOT NULL,
                account_name VARCHAR(255) NOT NULL,
                PRIMARY KEY(uuid)
            )
        SQL);

        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN company_accounts.uuid IS '(DC2Type:uuid)'
        SQL);

        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN company_accounts.address_uuid IS '(DC2Type:uuid)'
        SQL);

        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN company_accounts.client_uuid IS '(DC2Type:uuid)'
        SQL);


        // ============================================================================
        // ALTERATIONS FROM: Version20250726193357.php
        // ============================================================================

        // Alter table: client
        // Source: Version20250726193357.php
        // Add columns: is_delete, is_company
        $this->addSql(<<<'SQL'
            ALTER TABLE client ADD is_delete BOOLEAN DEFAULT false
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE client ADD is_company BOOLEAN DEFAULT false
        SQL);

        // ============================================================================
        // FOREIGN KEY CONSTRAINTS
        // Source: Version20250525190800.php, Version20250615123148.php
        // ============================================================================

        // Foreign keys from Version20250525190800.php
        $this->addSql(<<<'SQL'
            DO $$
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_5f732bfc19eb6921') THEN
                    ALTER TABLE client_address ADD CONSTRAINT FK_5F732BFC19EB6921 FOREIGN KEY (client_uuid) REFERENCES client (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE;
                END IF;
            END $$;
        SQL);
        $this->addSql(<<<'SQL'
            DO $$
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_client_address') THEN
                    ALTER TABLE client ADD CONSTRAINT FK_CLIENT_ADDRESS FOREIGN KEY (address_uuid) REFERENCES client_address (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE;
                END IF;
            END $$;
        SQL);
        $this->addSql(<<<'SQL'
            DO $$
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_8c9f36104584665a') THEN
                    ALTER TABLE file ADD CONSTRAINT FK_8C9F36104584665A FOREIGN KEY (product_uuid) REFERENCES product (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE;
                END IF;
            END $$;
        SQL);
        $this->addSql(<<<'SQL'
            DO $$
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_c53d045f4584665a') THEN
                    ALTER TABLE image ADD CONSTRAINT FK_C53D045F4584665A FOREIGN KEY (product_uuid) REFERENCES product (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE;
                END IF;
            END $$;
        SQL);
        $this->addSql(<<<'SQL'
            DO $$
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_d34a04adc54c8c93') THEN
                    ALTER TABLE product ADD CONSTRAINT FK_D34A04ADC54C8C93 FOREIGN KEY (type_uuid) REFERENCES product_type (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE;
                END IF;
            END $$;
        SQL);
        $this->addSql(<<<'SQL'
            DO $$
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_97601f8393cb796c') THEN
                    ALTER TABLE template ADD CONSTRAINT FK_97601F8393CB796C FOREIGN KEY (file_uuid) REFERENCES file (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE;
                END IF;
            END $$;
        SQL);
        $this->addSql(<<<'SQL'
            DO $$
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_24fb228b5da0fb8') THEN
                    ALTER TABLE template_key ADD CONSTRAINT FK_24FB228B5DA0FB8 FOREIGN KEY (template_uuid) REFERENCES template (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE;
                END IF;
            END $$;
        SQL);

        // Foreign keys from Version20250615123148.php
        $this->addSql(<<<'SQL'
            DO $$
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_8263ffce3da5256d') THEN
                    ALTER TABLE product_images ADD CONSTRAINT FK_8263FFCE3DA5256D FOREIGN KEY (image_uuid) REFERENCES image (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE;
                END IF;
            END $$;
        SQL);

        // Foreign keys for company_accounts table
        $this->addSql(<<<'SQL'
            DO $$
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_company_accounts_address') THEN
                    ALTER TABLE company_accounts ADD CONSTRAINT FK_COMPANY_ACCOUNTS_ADDRESS FOREIGN KEY (address_uuid) REFERENCES client_address (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE;
                END IF;
            END $$;
        SQL);
        $this->addSql(<<<'SQL'
            DO $$
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'fk_company_accounts_client') THEN
                    ALTER TABLE company_accounts ADD CONSTRAINT FK_COMPANY_ACCOUNTS_CLIENT FOREIGN KEY (client_uuid) REFERENCES client (uuid) NOT DEFERRABLE INITIALLY IMMEDIATE;
                END IF;
            END $$;
        SQL);


       
    }

    public function down(Schema $schema): void
    {
        // Drop all foreign key constraints
        $this->addSql('ALTER TABLE client_address DROP CONSTRAINT IF EXISTS FK_5F732BFC19EB6921');
        $this->addSql('ALTER TABLE client DROP CONSTRAINT IF EXISTS FK_CLIENT_ADDRESS');
        $this->addSql('ALTER TABLE file DROP CONSTRAINT IF EXISTS FK_8C9F36104584665A');
        $this->addSql('ALTER TABLE image DROP CONSTRAINT IF EXISTS FK_C53D045F4584665A');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT IF EXISTS FK_D34A04ADC54C8C93');
        $this->addSql('ALTER TABLE template DROP CONSTRAINT IF EXISTS FK_97601F8393CB796C');
        $this->addSql('ALTER TABLE template_key DROP CONSTRAINT IF EXISTS FK_24FB228B5DA0FB8');
        $this->addSql('ALTER TABLE product_images DROP CONSTRAINT IF EXISTS FK_8263FFCE3DA5256D');
        $this->addSql('ALTER TABLE company_accounts DROP CONSTRAINT IF EXISTS FK_COMPANY_ACCOUNTS_ADDRESS');
        $this->addSql('ALTER TABLE company_accounts DROP CONSTRAINT IF EXISTS FK_COMPANY_ACCOUNTS_CLIENT');

        // Drop all tables in reverse order
        $this->addSql('DROP TABLE company_accounts');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE product_images');
        $this->addSql('DROP TABLE template_key');
        $this->addSql('DROP TABLE template');
        $this->addSql('DROP TABLE history');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_type');
        $this->addSql('DROP TABLE client_address');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE company_accounts');
    }
}