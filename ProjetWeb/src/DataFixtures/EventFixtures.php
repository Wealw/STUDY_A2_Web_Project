<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Repository\EventTypeRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class EventFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * @var \Faker\Generator
     */
    private $faker;
    /**
     * @var EventTypeRepository
     */
    private $repository;

    public function __construct(EventTypeRepository $repository)
    {
        $this->faker = Factory::create('fr_FR');
        $this->repository = $repository;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 50; $i++) {
            $event = new Event();
            $eventType = $this->repository->find(70);

            dd($eventType);

            $date = $this->faker->dateTimeInInterval('-1 year', '+11 months');

            $event
                ->setEventName($this->faker->name)
                ->setEventDescription($this->faker->sentences(8, true))
                ->setEventImagePath($this->faker->imageUrl())
                ->setEventLocation($this->faker->city)
                ->setEventPrice($this->faker->numberBetween(0, 30))
                ->setEventDate($this->faker->dateTimeInInterval('-1 year', '+11 months'))
                ->setEventCreatedAt($this->faker->dateTimeInInterval($date, '-1 month'))
                ->setEventModifiedAt(null)
                ->setEventCreatedBy($this->faker->numberBetween(1, 8))
                ->setEventIsVisible(1)
                ->setEventType($eventType)
                ->setEventPeriode(null);

            $manager->persist($event);
        }

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [EventTypeFixtures::class];
    }
}
