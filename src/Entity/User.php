<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Trait\HydrateTrait;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints\Image;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[Vich\Uploadable]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use HydrateTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 150)]
    private ?string $firstname = null;

    #[ORM\Column(length: 200)]
    private ?string $lastname = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    #[Vich\UploadableField(mapping: "uploads", fileNameProperty: "avatar")]
    // #[Assert\Image()] doesn't work
    private ?File $avatarFile = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;


#################### Relations ###################################################

    #[ORM\OneToOne(inversedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Address $address = null;
    
    // /**
    //  * @var Collection<int, EventSubscription>
    //  */
    // #[ORM\OneToMany(targetEntity: EventSubscription::class, mappedBy: 'userSubscriptor')]
    // private Collection $eventSubscriptions;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'userOrganisator')]
    private Collection $eventsOrganized;

    /**
     * @var Collection<int, EventOccurrenceSubscription>
     */
    #[ORM\OneToMany(targetEntity: EventOccurrenceSubscription::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $occurrencesSubscriptions;


#####################  Functions #########################################

    public function __construct(array $init = [])
    {
        $this->hydrate($init);
        // $this->eventSubscriptions = new ArrayCollection();
        $this->eventsOrganized = new ArrayCollection();
        $this->occurrencesSubscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return Collection<int, EventSubscription>
     */
    /*
    public function getEventSubscriptions(): Collection
    {
        return $this->eventSubscriptions;
    }

    public function addEventSubscription(EventSubscription $eventSubscription): static
    {
        if (!$this->eventSubscriptions->contains($eventSubscription)) {
            $this->eventSubscriptions->add($eventSubscription);
            $eventSubscription->setUserSubscriptor($this);
        }

        return $this;
    }

    public function removeEventSubscription(EventSubscription $eventSubscription): static
    {
        if ($this->eventSubscriptions->removeElement($eventSubscription)) {
            // set the owning side to null (unless already changed)
            if ($eventSubscription->getUserSubscriptor() === $this) {
                $eventSubscription->setUserSubscriptor(null);
            }
        }

        return $this;
    }
*/
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
     * @return Collection<int, Event>
     */
    public function getEventsOrganized(): Collection
    {
        return $this->eventsOrganized;
    }

    public function addEventsOrganized(Event $eventsOrganized): static
    {
        if (!$this->eventsOrganized->contains($eventsOrganized)) {
            $this->eventsOrganized->add($eventsOrganized);
            $eventsOrganized->setUserOrganisator($this);
        }

        return $this;
    }

    public function removeEventsOrganized(Event $eventsOrganized): static
    {
        if ($this->eventsOrganized->removeElement($eventsOrganized)) {
            // set the owning side to null (unless already changed)
            if ($eventsOrganized->getUserOrganisator() === $this) {
                $eventsOrganized->setUserOrganisator(null);
            }
        }

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    

    /**
     * Get the value of avatarFile
     */
    public function getAvatarFile(): ?File
    {
        return $this->avatarFile;
    }

    /**
     * Set the value of avatarFile
     */
    public function setAvatarFile(?File $avatarFile): self
    {
        
        $this->avatarFile = $avatarFile;

        // if(null !== $avatarFile){
        if($this->avatarFile instanceof UploadedFile){
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    /**
     * Get the value of updatedAt
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt
     */
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, EventOccurrenceSubscription>
     */
    public function getOccurrencesSubscriptions(): Collection
    {
        return $this->occurrencesSubscriptions;
    }

    public function addOccurrencesSubscription(EventOccurrenceSubscription $occurrencesSubscription): static
    {
        if (!$this->occurrencesSubscriptions->contains($occurrencesSubscription)) {
            $this->occurrencesSubscriptions->add($occurrencesSubscription);
            $occurrencesSubscription->setUser($this);
        }

        return $this;
    }

    public function removeOccurrencesSubscription(EventOccurrenceSubscription $occurrencesSubscription): static
    {
        if ($this->occurrencesSubscriptions->removeElement($occurrencesSubscription)) {
            // set the owning side to null (unless already changed)
            if ($occurrencesSubscription->getUser() === $this) {
                $occurrencesSubscription->setUser(null);
            }
        }

        return $this;
    }
}
