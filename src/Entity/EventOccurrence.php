<?php

namespace App\Entity;

use App\Trait\HydrateTrait;
use App\Repository\EventOccurrenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventOccurrenceRepository::class)]
class EventOccurrence
{
    use HydrateTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateStart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateEnd = null;

    ###################### Relations

    #[ORM\ManyToOne(inversedBy: 'Occurrences')]
    private ?Event $event = null;

    /**
     * @var Collection<int, EventOccurrenceSubscription>
     */
    #[ORM\OneToMany(targetEntity: EventOccurrenceSubscription::class, mappedBy: 'eventOccurrence')]
    private Collection $subscriptions;

    ####################### Functions

    public function __construct(array $init = [])
    {
        $this->hydrate($init);
        $this->subscriptions = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): static
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeInterface $dateEnd): static
    {
        $this->dateEnd = $dateEnd;

        return $this;
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

    /**
     * @return Collection<int, EventOccurrenceSubscription>
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(EventOccurrenceSubscription $subscription): static
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions->add($subscription);
            $subscription->setEventOccurrence($this);
        }

        return $this;
    }

    public function removeSubscription(EventOccurrenceSubscription $subscription): static
    {
        if ($this->subscriptions->removeElement($subscription)) {
            // set the owning side to null (unless already changed)
            if ($subscription->getEventOccurrence() === $this) {
                $subscription->setEventOccurrence(null);
            }
        }

        return $this;
    }
}
