<?php

namespace App\DataFixtures;

use App\Entity\EventType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class EventTypeFixtures extends Fixture
{

    /**
     * @var \Faker\Generator
     */
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i < 8; $i++) {
            $eventType = new EventType();
            $eventType->setEventTypeName($this->faker->word);

            $manager->persist($eventType);
        }

        $manager->flush();
    }
}
