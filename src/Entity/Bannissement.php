<?php

namespace App\Entity;

use App\Repository\BannissementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BannissementRepository::class)]
class Bannissement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $raison = null;

    #[ORM\ManyToOne(inversedBy: 'banCrees')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $banneur = null;

    #[ORM\OneToOne(inversedBy: 'banRecu', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $banni = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateTimeFin = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRaison(): ?string
    {
        return $this->raison;
    }

    public function setRaison(string $raison): self
    {
        $this->raison = $raison;

        return $this;
    }

    public function getBanneur(): ?Utilisateur
    {
        return $this->banneur;
    }

    public function setBanneur(?Utilisateur $banneur): self
    {
        $this->banneur = $banneur;

        return $this;
    }

    public function getBanni(): ?Utilisateur
    {
        return $this->banni;
    }

    public function setBanni(Utilisateur $banni): self
    {
        $this->banni = $banni;

        return $this;
    }

    public function getDateTimeFin(): ?\DateTimeInterface
    {
        return $this->dateTimeFin;
    }

    public function setDateTimeFin(\DateTimeInterface $dateTimeFin): self
    {
        $this->dateTimeFin = $dateTimeFin;

        return $this;
    }
}
