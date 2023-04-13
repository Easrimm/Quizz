<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[UniqueEntity(fields: ['pseudo'], message: 'There is already an account with this pseudo')]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 180)]
    private string $email;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'Utilisateur', targetEntity: QuizzEffectue::class, orphanRemoval: true)]
    private Collection $quizzEffectues;

    #[ORM\Column(type: 'boolean')]
    private $is_verified = false;

    #[ORM\Column(type: 'string', length: 100)]
    private $resetToken;

    #[ORM\ManyToMany(targetEntity: Proposition::class, inversedBy: 'utilisateurs')]
    private Collection $propositions;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'estAmisDe')]
    private Collection $amis;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'amis')]
    private Collection $estAmisDe;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Notification::class, orphanRemoval: true)]
    private Collection $notifications;

    #[ORM\OneToMany(mappedBy: 'envoyeur', targetEntity: Message::class, orphanRemoval: true)]
    private Collection $messagesEnvoyes;

    #[ORM\OneToMany(mappedBy: 'destinataire', targetEntity: Message::class)]
    private Collection $messagesRecus;

    #[ORM\OneToMany(mappedBy: 'banneur', targetEntity: Bannissement::class, orphanRemoval: true)]
    private Collection $banCrees;

    #[ORM\OneToOne(mappedBy: 'banni', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Bannissement $banRecu = null;
    

    public function __construct()
    {
        $this->quizzEffectues = new ArrayCollection();
        $this->propositions = new ArrayCollection();
        $this->amis = new ArrayCollection();
        $this->estAmisDe = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->messagesEnvoyes = new ArrayCollection();
        $this->messagesRecus = new ArrayCollection();
        $this->banCrees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->pseudo;
    }

    public function getEmail(): ?string{
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, QuizzEffectue>
     */
    public function getQuizzEffectues(): Collection
    {
        return $this->quizzEffectues;
    }

    public function addQuizzEffectue(QuizzEffectue $quizzEffectue): self
    {
        if (!$this->quizzEffectues->contains($quizzEffectue)) {
            $this->quizzEffectues->add($quizzEffectue);
            $quizzEffectue->setUtilisateur($this);
        }

        return $this;
    }

    public function removeQuizzEffectue(QuizzEffectue $quizzEffectue): self
    {
        if ($this->quizzEffectues->removeElement($quizzEffectue)) {
            // set the owning side to null (unless already changed)
            if ($quizzEffectue->getUtilisateur() === $this) {
                $quizzEffectue->setUtilisateur(null);
            }
        }

        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->is_verified;
    }

    public function setIsVerified(bool $is_verified): self
    {
        $this->is_verified = $is_verified;
        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    /**
     * @return Collection<int, Proposition>
     */
    public function getPropositions(): Collection
    {
        return $this->propositions;
    }

    public function addProposition(Proposition $proposition): self
    {
        if (!$this->propositions->contains($proposition)) {
            $this->propositions->add($proposition);
        }

        return $this;
    }

    public function removeProposition(Proposition $proposition): self
    {
        $this->propositions->removeElement($proposition);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getAmis(): Collection
    {
        return $this->amis;
    }

    public function addAmi(self $ami): self
    {
        if (!$this->amis->contains($ami)) {
            $this->amis->add($ami);
        }

        return $this;
    }

    public function removeAmi(self $ami): self
    {
        $this->amis->removeElement($ami);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getEstAmisDe(): Collection
    {
        return $this->estAmisDe;
    }

    public function addEstAmisDe(self $estAmisDe): self
    {
        if (!$this->estAmisDe->contains($estAmisDe)) {
            $this->estAmisDe->add($estAmisDe);
            $estAmisDe->addAmi($this);
        }

        return $this;
    }

    public function removeEstAmisDe(self $estAmisDe): self
    {
        if ($this->estAmisDe->removeElement($estAmisDe)) {
            $estAmisDe->removeAmi($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setUtilisateur($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getUtilisateur() === $this) {
                $notification->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessagesEnvoyes(): Collection
    {
        return $this->messagesEnvoyes;
    }

    public function addMessagesEnvoye(Message $messagesEnvoye): self
    {
        if (!$this->messagesEnvoyes->contains($messagesEnvoye)) {
            $this->messagesEnvoyes->add($messagesEnvoye);
            $messagesEnvoye->setEnvoyeur($this);
        }

        return $this;
    }

    public function removeMessagesEnvoye(Message $messagesEnvoye): self
    {
        if ($this->messagesEnvoyes->removeElement($messagesEnvoye)) {
            // set the owning side to null (unless already changed)
            if ($messagesEnvoye->getEnvoyeur() === $this) {
                $messagesEnvoye->setEnvoyeur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessagesRecus(): Collection
    {
        return $this->messagesRecus;
    }

    public function addMessagesRecu(Message $messagesRecu): self
    {
        if (!$this->messagesRecus->contains($messagesRecu)) {
            $this->messagesRecus->add($messagesRecu);
            $messagesRecu->setDestinataire($this);
        }

        return $this;
    }

    public function removeMessagesRecu(Message $messagesRecu): self
    {
        if ($this->messagesRecus->removeElement($messagesRecu)) {
            // set the owning side to null (unless already changed)
            if ($messagesRecu->getDestinataire() === $this) {
                $messagesRecu->setDestinataire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Bannissement>
     */
    public function getBanCrees(): Collection
    {
        return $this->banCrees;
    }

    public function addBanCree(Bannissement $banCree): self
    {
        if (!$this->banCrees->contains($banCree)) {
            $this->banCrees->add($banCree);
            $banCree->setBanneur($this);
        }

        return $this;
    }

    public function removeBanCree(Bannissement $banCree): self
    {
        if ($this->banCrees->removeElement($banCree)) {
            // set the owning side to null (unless already changed)
            if ($banCree->getBanneur() === $this) {
                $banCree->setBanneur(null);
            }
        }

        return $this;
    }

    public function getBanRecu(): ?Bannissement
    {
        return $this->banRecu;
    }

    public function setBanRecu(Bannissement $banRecu): self
    {
        // set the owning side of the relation if necessary
        if ($banRecu->getBanni() !== $this) {
            $banRecu->setBanni($this);
        }

        $this->banRecu = $banRecu;

        return $this;
    }
}
