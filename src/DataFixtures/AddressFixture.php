<?php

namespace App\DataFixtures;

use App\Entity\Address;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AddressFixture extends Fixture
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

        foreach($gamingPlaceData as $PlaceData){
            $address = new Address([
                // "locality"=> $PlaceData["address"]["locality"],
                "street" => $PlaceData["address"]["street"],
                "number" => $PlaceData["address"]["number"],
                "postCode" => $PlaceData["address"]["postCode"],
                "city"=>$PlaceData["address"]["city"],
                "country" => "Belgium",
                "lat" => $PlaceData["address"]["lat"],
                "lon"=>$PlaceData["address"]["lon"]
            ]);

            $manager->persist($address);
            $this->addReference("address".$PlaceData["name"] ,$address);
        }
        
        for ($i = 0; $i < 100; $i++) { 
            $address = new Address([
                "street" => $faker->streetName(),
                "number" => $faker->buildingNumber(),
                "postCode" => $faker->postcode(),
                "city"=>$faker->city(),
                "country"=> "Belgium",
            ]);
            $manager->persist($address);

            // addReference, always after persist (it must be known by doctrine to make the reference)
            $this->addReference("address$i" ,$address);
        }

        $manager->flush();
    }
}
