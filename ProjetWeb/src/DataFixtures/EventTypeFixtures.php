<?php

namespace App\DataFixtures;

use App\Entity\Social\Comment;
use App\Entity\Social\Event;
use App\Entity\Social\EventType;
use App\Entity\Social\Participation;
use App\Entity\Social\Picture;
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

        for ($i = 0; $i < 4; $i++) {
            $eventType = new EventType();
            $eventType->setEventTypeName($this->faker->word);

            $manager->persist($eventType);

            $rand = $this->faker->numberBetween(3, 6);
            for ($j = 0; $j < $rand; $j++) {
                $event = new Event();
                $date = $this->faker->dateTimeInInterval('-1 month', '+10 months');

                $event
                    ->setEventName($this->faker->word)
                    ->setEventDescription($this->faker->sentences(8, true))
                    ->setEventImagePath($this->faker->imageUrl())
                    ->setEventLocation($this->faker->city)
                    ->setEventPrice($this->faker->numberBetween(0, 30))
                    ->setEventDate($this->faker->dateTimeInInterval($date, '+5 months'))
                    ->setEventCreatedAt($this->faker->dateTimeInInterval($date, '-1 month'))
                    ->setEventModifiedAt(null)
                    ->setEventCreatedBy($this->faker->numberBetween(1, 12))
                    ->setEventIsVisible(1)
                    ->setEventType($eventType)
                    ->setEventPeriod(null);

                $manager->persist($event);

                $randPicture = $this->faker->numberBetween(0, 3);
                for ($k = 0; $k < $randPicture; $k++) {
                    $datePicture = $this->faker->dateTimeInInterval($date, '+1 month');

                    $picture = new Picture();
                    $picture
                        ->setPictureName($this->faker->word)
                        ->setPictureDescription($this->faker->sentences(3, true))
                        ->setPicturePostedAt($datePicture)
                        ->setPictureModifiedAt(null)
                        ->setPicturePath($this->faker->imageUrl())
                        ->setPictureUserId($this->faker->numberBetween(1, 12))
                        ->setEvent($event);

                    $manager->persist($picture);

                    for ($l = 0; $l < $randPicture; $l++) {
                        $comment = new Comment();
                        $comment
                            ->setCommentText($this->faker->sentences(3, true))
                            ->setCommentPostedAt($this->faker->dateTimeInInterval($datePicture, '+1 week'))
                            ->setCommentUserId($this->faker->numberBetween(1, 12))
                            ->setPicture($picture);

                        $manager->persist($comment);
                    }
                }

                for ($k = 0; $k < $randPicture; $k++) {
                    $participation = new Participation();
                    $participation
                        ->setParticipationUserId($this->faker->numberBetween(1, 12))
                        ->setEvent($event);

                    $manager->persist($participation);
                }

            }

        }

        $manager->flush();
    }
}
