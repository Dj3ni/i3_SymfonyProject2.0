<?php

namespace App\Entity;

use App\Enum\EventType;
use App\Enum\RecurrenceType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Trait\HydrateTrait;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event

{
########################## Imports #########################################
    use HydrateTrait;

########################## Properties #########################################
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[SerializedName("start")]
    private ?\DateTimeInterface $dateStart = null;


    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[SerializedName("end")]
    private ?\DateTimeInterface $dateEnd = null;

    #[ORM\Column(type: 'string', enumType: RecurrenceType::class)] 
    private RecurrenceType $recurrenceType;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $recurrenceEnd = null; // Date à laquelle la récurrence se termine

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $recurrenceCount = null; // Nombre d'occurrences

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $fee = "0.00";

    //We need to instruct Doctrine that we have an Enum otherwise, it doesn't understand
    #[ORM\Column(type: 'string', enumType: EventType::class)] 
    private EventType $eventType;


#################### Relations ###################################################

    // /**
    //  * @var Collection<int, EventSubscription>
    //  */
    // #[ORM\OneToMany(targetEntity: EventSubscription::class, mappedBy: 'eventSubscripted')]
    // private Collection $subscriptions;

    /**
     * @var Collection<int, EventPlace>
     */
    #[ORM\OneToMany(targetEntity: EventPlace::class, mappedBy: 'event', cascade:['persist', 'remove'])]
    private Collection $eventPlaces;

    #[ORM\ManyToOne(inversedBy: 'eventsOrganized')]
    private ?User $userOrganisator = null;

    /**
     * @var Collection<int, EventOccurrence>
     */
    #[ORM\OneToMany(targetEntity: EventOccurrence::class, mappedBy: 'event', cascade:['remove'])]
    private Collection $occurrences;


#####################  Functions #########################################
    
    public function __construct(array $init = [])
    {
        $this->hydrate($init);
        // $this->subscriptions = new ArrayCollection();
        $this->eventPlaces = new ArrayCollection();
        $this->occurrences = new ArrayCollection();
        
    }
    #[Assert\Callback] //will be called when entity validated
    public function isDatesValid(ExecutionContextInterface $context): void
    {
        // Context let us send a personalized msg and will be checked with isValid in form
        if($this->dateEnd < $this->dateStart){
            $context->buildViolation("Date End must me after Date start")
                    ->atPath("dateEnd") //error field
                    ->addViolation();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getFee(): ?string
    {
        return $this->fee;
    }

    public function setFee(string $fee): static
    {
        $this->fee = $fee;

        return $this;
    }

    public function getEventType(): eventType
    {
        return $this->eventType;
    }

    public function setEventType(eventType $eventType): self
    {
        $this->eventType = $eventType;

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
            $eventPlace->setEvent($this);
        }

        return $this;
    }

    public function removeEventPlace(EventPlace $eventPlace): static
    {
        if ($this->eventPlaces->removeElement($eventPlace)) {
            // set the owning side to null (unless already changed)
            if ($eventPlace->getEvent() === $this) {
                $eventPlace->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of recurrenceType
     */
    public function getRecurrenceType(): RecurrenceType
    {
        return $this->recurrenceType;
    }

    /**
     * Set the value of recurrenceType
     */
    public function setRecurrenceType(RecurrenceType $recurrenceType = RecurrenceType::NONE) : self
    {
        $this->recurrenceType = $recurrenceType;

        return $this;
    }

    /**
     * Get the value of recurrenceEnd
     */
    public function getRecurrenceEnd(): ?\DateTimeInterface
    {
        return $this->recurrenceEnd;
    }

    /**
     * Set the value of recurrenceEnd
     */
    public function setRecurrenceEnd(?\DateTimeInterface $recurrenceEnd): self
    {
        $this->recurrenceEnd = $recurrenceEnd;

        return $this;
    }

    /**
     * Get the value of recurrenceCount
     */
    public function getRecurrenceCount(): ?int
    {
        return $this->recurrenceCount;
    }

    /**
     * Set the value of recurrenceCount
     */
    public function setRecurrenceCount(?int $recurrenceCount): self
    {
        $this->recurrenceCount = $recurrenceCount;

        return $this;
    }

    public function getUserOrganisator(): ?User
    {
        return $this->userOrganisator;
    }

    public function setUserOrganisator(?User $userOrganisator): static
    {
        $this->userOrganisator = $userOrganisator;

        return $this;
    }

    /**
     * @return Collection<int, EventOccurrence>
     */
    public function getOccurrences(): Collection
    {
        return $this->occurrences;
    }

    public function addOccurrence(EventOccurrence $occurrence): static
    {
        if (!$this->occurrences->contains($occurrence)) {
            $this->occurrences->add($occurrence);
            $occurrence->setEvent($this);
        }

        return $this;
    }

    public function removeOccurrence(EventOccurrence $occurrence): static
    {
        if ($this->occurrences->removeElement($occurrence)) {
            // set the owning side to null (unless already changed)
            if ($occurrence->getEvent() === $this) {
                $occurrence->setEvent(null);
            }
        }

        return $this;
    }

}
