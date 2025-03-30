<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\DataFixtures\AddressFixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    // Hasher de pwd
    private UserPasswordHasherInterface $pwdHasher;

    // Insert it in the construct function
    public function __construct(UserPasswordHasherInterface $pwdHasher)
    {
        $this->pwdHasher = $pwdHasher;
    }
    
    // Load everything in the fixture
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create("fr_BE");
        
        // user
        for ($i = 0; $i < 10; $i++) { 
            $user = new User([
                "firstname" => $faker->firstName(),
                "lastname" => $faker->lastName(),
                "phoneNumber" => $faker->phoneNumber(),
                "email"=> "user". $i . "@mail.com",
            ]);
            $pwdHashed =$this->pwdHasher->hashPassword($user,"Password");
            $user->setPassword($pwdHashed);

            // Get the refered address ( before persist)
            $user->setAddress($this->getReference('address'.$i));
            
            $manager->persist($user);

            // add reference for event (after persist)
            $this->addReference("user$i", $user);
        }

        // admin
        for ($i = 10; $i < 20; $i++) { 
            $admin = new User([
                "firstname" => $faker->firstName(),
                "lastname" => $faker->lastName(),
                "phoneNumber" => $faker->phoneNumber(),
                "email"=> "admin". $i . "@mail.com",
            ]);
            $pwdHashed =$this->pwdHasher->hashPassword($admin,"Password");
            $admin->setPassword($pwdHashed);
            $manager->persist($admin);
            $admin->setRoles(["ROLE_ADMIN"]);
            $admin->setAddress($this->getReference('address'.$i));
        }
        $manager->flush();
    }

    // For the reference, we have to tell Doctrine to load fixture for the Address before User
    public function getDependencies()
    {
        return([AddressFixture::class]);
    }
}
