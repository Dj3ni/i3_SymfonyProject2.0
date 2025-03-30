<?php

namespace App\Entity;

use App\Trait\HydrateTrait;
use App\Repository\GamingPlaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GamingPlaceRepository::class)]
class GamingPlace
{
    use HydrateTrait;

########################## Properties #########################################

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $placeMax = null;

#################### Relations ###################################################

    #[ORM\OneToOne(inversedBy: 'gamingPlace', cascade:['persist', 'remove'])]
    private ?Address $address = null;

    /**
     * @var Collection<int, EventPlace>
     */
    #[ORM\OneToMany(targetEntity: EventPlace::class, mappedBy: 'gamingPlace', cascade:['persist', 'remove'])]
    private Collection $eventPlaces;

#####################  Functions #########################################

    public function __construct(array $init = [])
    {
        $this->hydrate($init);
        $this->eventPlaces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPlaceMax(): ?int
    {
        return $this->placeMax;
    }

    public function setPlaceMax(int $placeMax): static
    {
        $this->placeMax = $placeMax;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): static
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, EventPlace>
     */
    public function getEventPlaces(): Collection
    {
        return $this->eventPlaces;
    }

    public function addEventPlace(EventPlace $eventPlace): static
    {
        if (!$this->eventPlaces->contains($eventPlace)) {
            $this->eventPlaces->add($eventPlace);
            $eventPlace->setGamingPlace($this);
        }

        return $this;
    }

    public function removeEventPlace(EventPlace $eventPlace): static
    {
        if ($this->eventPlaces->removeElement($eventPlace)) {
            // set the owning side to null (unless already changed)
            if ($eventPlace->getGamingPlace() === $this) {
                $eventPlace->setGamingPlace(null);
            }
        }

        return $this;
    }


}
