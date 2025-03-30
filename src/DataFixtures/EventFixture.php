<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Event;
use App\Enum\EventType;
use App\Entity\GamingPlace;
use App\Enum\RecurrenceType;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class EventFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create("fr_BE");

        // Use Json file with content
        $fileSystem = new Filesystem();
        $filePath = __DIR__ .'/eventsFakeFixture.json';

        if (!$fileSystem->exists($filePath)){
            throw new FileException("Json file doesn't exists.");
        }

        $jsonData = file_get_contents($filePath);
        $eventsData = json_decode($jsonData, true);

        // Use data to do fixture
        $dateStart = new DateTime();

        foreach($eventsData as $eventData){
            $event = new Event([
                "title"=> $eventData["title"],
                "dateStart" => $dateStart->modify('+' . rand(0,5) . ' months'),
                "dateEnd" => (clone $dateStart)->modify('+' . rand(0,7) . ' days'),
                // "dateStart"=>$eventData[new \DateTime("dateStart")]->format('d-m-Y H:i'),
                // "dateEnd"=>$eventData[new \DateTime("dateEnd")]->format('d-m-Y H:i'),
                "fee"=>$faker->randomFloat(2,0,100),
                "description"=>$eventData["description"],
            ]);
            $organisator = $this->getReference("user0");
            $event->setEventType(EventType::cases()[rand(0,4)]);
            
            $event->setRecurrenceType(RecurrenceType::cases()[rand(0,4)]);
            $event->setUserOrganisator($organisator);
            $this->addReference($eventData["title"],$event);

            $manager->persist($event);

        }
        
        // for ($i = 0; $i < 10; $i++) {
            
        //     // $dateStart = $faker->dateTime();


        //     $event = new Event([
        //         "title" => $faker->catchPhrase(),
        //         "dateStart" => $dateStart->modify('+' . rand(0,7) . ' months'),
        //         "dateEnd" => (clone $dateStart)->modify('+' . rand(0,7) . ' days'),
        //         "recurrenceEnd"=>$faker->dateTime(),
        //         "recurrenceCount"=> 3,
        //         "description" => $faker->paragraph(),
        //         "fee"=>$faker->randomFloat(2,0,100),
        //     ]);

        //     $organisator = $this->getReference("user$i");
            
        //     $event->setEventType(EventType::cases()[rand(0,4)]);
            
        //     $event->setRecurrenceType(RecurrenceType::cases()[rand(0,4)]);
        //     $event->setUserOrganisator($organisator);
            
        //     $manager->persist($event);

        //     // Références
        //     $this->addReference("event$i", $event);
        // }

        $manager->flush();
    }

    public function getDependencies()
    {
        return([
            UserFixtures::class,
        ]);
    }
}
