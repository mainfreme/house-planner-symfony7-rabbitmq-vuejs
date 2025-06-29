<?php

namespace App\DataFixtures;


use App\Domain\Product\Entity\Product;
use App\Domain\Product\Entity\ProductType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Zakładamy, że istnieją jakieś typy produktów — możesz je dodać w osobnym fixerze i dodać jako zależność
        $type = new ProductType();
        $type->setName('Domek letniskowy');
        $manager->persist($type);

        for ($i = 1; $i <= 10; $i++) {
            $product = new Product();
            $product->setName('Produkt ' . $i);
            $product->setDescription('Opis produktu numer ' . $i);
            $product->setPrice(mt_rand(10000, 50000) . ' PLN');
            $product->setType($type);
            $product->setIsActive((bool)random_int(0, 1));
            $product->setParameters([
                'długość' => mt_rand(3, 6) . 'm',
                'szerokość' => mt_rand(2, 5) . 'm',
                'kolor' => ['brązowy', 'szary', 'biały'][array_rand(['brązowy', 'szary', 'biały'])]
            ]);
            $product->setUuid(Uuid::v4());

            $manager->persist($product);
        }

        $manager->flush();
    }
}
