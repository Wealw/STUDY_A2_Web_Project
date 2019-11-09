<?php

namespace App\DataFixtures;

use App\Entity\Merch\Command;
use App\Entity\Merch\CommandProduct;
use App\Entity\Merch\Product;
use App\Entity\Merch\ProductType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class MerchFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($i = 0; $i < 5; $i++)
        {
            $productType = new ProductType();
            $productType
                ->setProductTypeName($faker->word);
            $command = new Command();
            $command
                ->setCommandOrderedAt($faker->dateTime)
                ->setCommandUserId($faker->numberBetween(0,100));
            $manager->persist($productType);
            $manager->persist($command);
            $rand = 5;
            for($j = 0; $j < $faker->numberBetween(1, 15); $j++)
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
                if($rand > 3)
                {
                    $commandProduct = new CommandProduct();
                    $commandProduct
                        ->setCommand($command)
                        ->setProduct($product)
                        ->setQuantity($faker->numberBetween(1, 15));
                    $manager->persist($commandProduct);
                }
                $rand = $faker->numberBetween(0,10);
            }
        }
        $manager->flush();
    }
}
