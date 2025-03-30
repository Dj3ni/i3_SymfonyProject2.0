<?php

namespace App\Entity;

use App\Repository\EventOccurrenceSubscriptionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventOccurrenceSubscriptionRepository::class)]
class EventOccurrenceSubscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $subscriptionDate = null;

    #[ORM\Column]
    private array $occurrenceDates = [];

    #[ORM\Column(nullable: true)]
    private ?int $numberParticipants = null;

#################### Relations ###################################################
    
    #[ORM\ManyToOne(inversedBy: 'occurrencesSubscriptions', cascade:['remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'subscriptions', cascade:['remove'])]
    #[ORM\JoinColumn(nullable: true, onDelete:'CASCADE')]
    private ?EventOccurrence $eventOccurrence = null;

#####################  Functions ################################    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubscriptionDate(): ?\DateTimeImmutable
    {
        return $this->subscriptionDate;
    }

    public function setSubscriptionDate(\DateTimeImmutable $subscriptionDate): static
    {
        $this->subscriptionDate = $subscriptionDate;

        return $this;
    }

    public function getOccurrenceDates(): array
    {
        return $this->occurrenceDates;
    }

    public function setOccurrenceDates(array $occurrenceDates): static
    {
        $this->occurrenceDates = $occurrenceDates;

        return $this;
    }

    public function getNumberParticipants(): ?int
    {
        return $this->numberParticipants;
    }

    public function setNumberParticipants(int $numberParticipants): static
    {
        $this->numberParticipants = $numberParticipants;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getEventOccurrence(): ?EventOccurrence
    {
        return $this->eventOccurrence;
    }

    public function setEventOccurrence(?EventOccurrence $eventOccurrence): static
    {
        $this->eventOccurrence = $eventOccurrence;

        return $this;
    }
}
