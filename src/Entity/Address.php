<?php

namespace App\Entity;

use App\Trait\HydrateTrait;
use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    use HydrateTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $locality = null;

    #[ORM\Column(length: 255)]
    private ?string $street = null;

    #[ORM\Column(length: 10)]
    private ?string $number = null;

    #[ORM\Column]
    private ?int $postCode = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column(nullable: true)]
    private ?float $lat = null;
    
    #[ORM\Column(nullable: true)]
    private ?float $lon = null;
    
#################### Relations ###################################################


    #[ORM\OneToOne(mappedBy: 'address', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\OneToOne(mappedBy: 'address', cascade: ['persist', 'remove'])]
    private ?GamingPlace $gamingPlace = null;


#####################  Functions #########################################


    public function __construct(array $init = []){
        $this->hydrate($init);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocality(): ?string
    {
        return $this->locality;
    }

    public function setLocality(?string $locality): static
    {
        $this->locality = $locality;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }


    public function getPostCode(): ?int
    {
        return $this->postCode;
    }

    public function setPostCode(int $postCode): static
    {
        $this->postCode = $postCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setAddress(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getAddress() !== $this) {
            $user->setAddress($this);
        }

        $this->user = $user;

        return $this;
    }

    public function getGamingPlace(): ?GamingPlace
    {
        return $this->gamingPlace;
    }

    public function setGamingPlace(?GamingPlace $gamingPlace): static
    {
        // unset the owning side of the relation if necessary
        if ($gamingPlace === null && $this->gamingPlace !== null) {
            $this->gamingPlace->setAddress(null);
        }

        // set the owning side of the relation if necessary
        if ($gamingPlace !== null && $gamingPlace->getAddress() !== $this) {
            $gamingPlace->setAddress($this);
        }

        $this->gamingPlace = $gamingPlace;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(?float $lat): static
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLon(): ?float
    {
        return $this->lon;
    }

    public function setLon(?float $lon): static
    {
        $this->lon = $lon;

        return $this;
    }

}
