<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Event;
use App\Entity\EventOccurrence;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EventOccurrenceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_BE");
        // Retrieve Event entities from existing fixtures or database
        $events = $manager->getRepository(Event::class)->findAll();


        for ($i = 0 ; $i < 50; $i++){
            $eventOccurrence = new EventOccurrence();

            // Random start and end dates (dateStart before dateEnd)
            $dateStart = $faker->dateTimeBetween('-1 year', 'now');
            $dateEnd = $faker->dateTimeBetween($dateStart, '+1 week');

            $eventOccurrence->setDateStart($dateStart);
            $eventOccurrence->setDateEnd($dateEnd);

            // Random event from the list of available events
            $randomEvent = $events[array_rand($events)];
            $eventOccurrence->setEvent($randomEvent);

            $manager->persist($eventOccurrence);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return[
            EventFixture::class,
        ];
    }

}
