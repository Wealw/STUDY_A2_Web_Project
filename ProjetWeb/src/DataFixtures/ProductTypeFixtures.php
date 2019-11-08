<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\ProductType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class ProductTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($i = 0; $i < 5; $i++)
        {
            $productType = new ProductType();
            $productType
                ->setProductTypeName($faker->word);

            $manager->persist($productType);
            for($j = 0; $j < $faker->numberBetween(0, 7); $j++)
            {
                $product = new Product();
                $product
                    ->setProductName($faker->name)
                    ->setProductDescription($faker->word)
                    ->setProductPrice($faker->numberBetween(4,130))
                    ->setProductInventory($faker->numberBetween(0, 160))
                    ->setProductImagePath($faker->imageUrl())
                    ->setProductType($productType);
                $manager->persist($product);
            }
        }
        $manager->flush();
    }
}
