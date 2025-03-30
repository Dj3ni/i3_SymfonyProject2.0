<?php

namespace App\Entity;

use App\Repository\EventPlaceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventPlaceRepository::class)]
class EventPlace
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

#################### Relations ###################################################


    #[ORM\ManyToOne(inversedBy: 'eventPlaces', cascade:['persist', 'remove'])]
    private ?Event $event = null;

    #[ORM\ManyToOne(inversedBy: 'eventPlaces', cascade:['persist', 'remove'])]
    private ?GamingPlace $gamingPlace = null;

#####################  Functions #########################################


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): static
    {
        $this->event = $event;

        return $this;
    }

    public function getGamingPlace(): ?GamingPlace
    {
        return $this->gamingPlace;
    }

    public function setGamingPlace(?GamingPlace $gamingPlace): static
    {
        $this->gamingPlace = $gamingPlace;

        return $this;
    }
}
