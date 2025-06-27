<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250525190849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {

        $this->addSql(<<<'SQL'
            INSERT INTO product_type (id, name, link, is_public) VALUES
            (1, 'Altany', 'altany', true),
            (2, 'Pergole', 'pergole', true),
            (3, 'Donice', 'donice', true),
            (4, 'Ławki', 'lawki', true),
            (5, 'Kwietniki', 'kwietniki', true);
        SQL);

        $this->addSql(<<<'SQL'
            INSERT INTO product (id, name, description, price, type_id, is_active, parameters, uuid) VALUES
            (1, 'Altana ogrodowa Roma', 'Altana sześciokątna z drewna świerkowego.', '5200.00', 1, true, '{"szerokosc": "3m", "wysokosc": "2.5m"}', '3b6f1cbe-cf9b-44a7-beb3-1a491d5deaa4'),
            (2, 'Pergola drewniana Toscana', 'Pergola z drewna sosnowego, idealna na winorośl.', '1200.00', 2, true, '{"dlugosc": "2m", "wysokosc": "2.2m"}', '7c21d188-bc0c-4437-962f-0798cf40d746'),
            (3, 'Donica kwadratowa DekoWood', 'Donica ogrodowa z drewna impregnowanego.', '300.00', 3, true, '{"szerokosc": "0.5m", "glebokosc": "0.4m"}', '6dc2907c-99df-4e46-a507-bb6f5b63fa01'),
            (4, 'Ławka parkowa Verona', 'Ławka z oparciem, drewno + stal malowana proszkowo.', '850.00', 4, true, '{"dlugosc": "1.5m", "material": "drewno+stal"}', 'be0d70d2-4e36-4b60-b9cb-d9ea3efb9f24'),
            (5, 'Kwietnik wiszący Flora', 'Drewniany kwietnik do zawieszenia na ścianie.', '150.00', 5, true, '{"wysokosc": "0.7m", "kolor": "ciemny brąz"}', '87a4b490-cf53-4cbe-b648-04e3d899189e'),
            (6, 'Altana drewniana Oslo', 'Duża altana ogrodowa z podłogą i barierkami.', '7800.00', 1, true, '{"szerokosc": "4m", "powierzchnia": "12m2"}', 'fab1a7a6-2b85-4b99-8fd1-eeb17d7e2df4'),
            (7, 'Pergola narożna Nova', 'Stylowa pergola na narożnik ogrodu.', '1800.00', 2, true, '{"material": "modrzew", "ksztalt": "L"}', '9c5de70d-193e-48fa-a318-d580c3f938c7'),
            (8, 'Donica drewniana Rustica', 'Rustykalna donica z desek sosnowych.', '270.00', 3, true, '{"dlugosc": "1m", "głębokość": "0.4m"}', '6a8f1293-8fc6-4c35-a823-cf3e88ec7ea6'),
            (9, 'Ławka bez oparcia Classic', 'Ławka bez oparcia, prosta konstrukcja.', '600.00', 4, true, '{"dlugosc": "1.2m", "kolor": "naturalny"}', 'a4a2619c-12f0-469e-b7c1-842f5fd6e5b1'),
            (10, 'Kwietnik schodkowy GardenStep', 'Kwietnik na 3 poziomy doniczek.', '240.00', 5, true, '{"poziomy": 3, "wysokosc": "0.9m"}', 'f5f7f5de-4d3f-402b-9be7-0f826f6a2089');
        SQL);

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
