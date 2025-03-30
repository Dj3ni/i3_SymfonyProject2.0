<?php

namespace App\DataFixtures;

use App\Entity\GamingPlace;
use App\DataFixtures\AddressFixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

use function PHPUnit\Framework\throwException;

class GamingPlaceFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create("fr_BE");

        // Use Json file with content
        $fileSystem = new Filesystem();
        $filePath = __DIR__ .'/gamingPlaceFakeAddress.json';

        if (!$fileSystem->exists($filePath)){
            throw new FileException("Json file doesn't exists.");
        }

        $jsonData = file_get_contents($filePath);
        $gamingPlaceData = json_decode($jsonData, true);

        // Use data to do fixture

        foreach($gamingPlaceData as $placeData){
            $gamingPlace = new GamingPlace([
                "name"=> $placeData["name"],
                "type"=> $placeData["description"],
                "description"=> $faker->paragraph(),
                "placeMax"=> $faker->randomNumber(3, false),
            ]);
            $gamingPlace->setAddress($this->getReference("address".$placeData["name"]));
            $manager->persist($gamingPlace);
            $this->addReference($placeData["name"],$gamingPlace);
        }

        // for ($i=30; $i < 40; $i++) { 
        //     $gamingPlace = new GamingPlace([
        //         "name" => $faker->company(),
        //         "type" => $faker->word(),
        //         "description" => $faker->paragraph(),
        //         "placeMax"=>$faker->randomNumber(3, false),
        //     ]);
        //     $manager->persist($gamingPlace);
        //     $gamingPlace->setAddress($this->getReference("address$i"));
        //     // References
        //     $this->addReference("gamingPlace$i", $gamingPlace);
        // }

        $manager->flush();
    }

    public function getDependencies()
    {
        return([AddressFixture::class]);
    }
}
