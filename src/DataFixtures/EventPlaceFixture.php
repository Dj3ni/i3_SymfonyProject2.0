<?php

namespace App\DataFixtures;


use App\Entity\EventPlace;
use App\DataFixtures\EventFixture;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\GamingPlaceFixture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class EventPlaceFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Use Json file with content
        $fileSystem = new Filesystem();
        $filePath = __DIR__ .'/eventsFakeFixture.json';

        if (!$fileSystem->exists($filePath)){
            throw new FileException("Json file doesn't exists.");
        }

        $jsonData = file_get_contents($filePath);
        $eventsData = json_decode($jsonData, true);

        foreach ($eventsData as $eventData){
            $event = $this->getReference($eventData["title"]);
            $gamingPlace = $this->getReference($eventData["gamingPlace"]["name"]);
            $eventPlace  = new EventPlace();
            $eventPlace->setEvent($event)
                        ->setGamingPlace($gamingPlace);
            $manager->persist($eventPlace);
        }

        // for ($i=0; $i < 10; $i++) { 
        //     $event = $this->getReference("event$i");
        //     foreach (range(0, rand(1,3)) as $j) { // I want 1 to 3 places for each event
        //         $gamingPlace = $this->getReference("gamingPlace".rand(30,39)); // intervals in GamePlace fixture
        //         // Create a new Object to link them
        //         $eventPlace = new EventPlace();
        //         $eventPlace->setEvent($event);
        //         $eventPlace->setGamingPlace($gamingPlace);
        //         $manager->persist($eventPlace);
        //     }
        // }

        $manager->flush();
    }


    public function getDependencies()
    {
        return([
            GamingPlaceFixture::class,
            EventFixture::class,
        ]);
    }
}
