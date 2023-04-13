<?php

namespace App\Entity;

use App\Repository\QuizzEffectueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizzEffectueRepository::class)]
class QuizzEffectue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $bonnesReponses = null;

    #[ORM\Column]
    private ?int $mauvaisesReponses = null;

    #[ORM\ManyToOne(inversedBy: 'quizzEffectues')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quizz $quizz = null;

    #[ORM\ManyToOne(inversedBy: 'quizzEffectues')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $Utilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBonnesReponses(): ?int
    {
        return $this->bonnesReponses;
    }

    public function setBonnesReponses(int $bonnesReponses): self
    {
        $this->bonnesReponses = $bonnesReponses;

        return $this;
    }

    public function getMauvaisesReponses(): ?int
    {
        return $this->mauvaisesReponses;
    }

    public function setMauvaisesReponses(int $mauvaisesReponses): self
    {
        $this->mauvaisesReponses = $mauvaisesReponses;

        return $this;
    }

    public function getQuizz(): ?Quizz
    {
        return $this->quizz;
    }

    public function setQuizz(?Quizz $quizz): self
    {
        $this->quizz = $quizz;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->Utilisateur;
    }

    public function setUtilisateur(?Utilisateur $Utilisateur): self
    {
        $this->Utilisateur = $Utilisateur;

        return $this;
    }
}
