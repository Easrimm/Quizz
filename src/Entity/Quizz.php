<?php

namespace App\Entity;

use App\Repository\QuizzRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizzRepository::class)]
class Quizz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $theme = null;

    #[ORM\Column]
    private ?int $difficulte = null;

    #[ORM\OneToMany(mappedBy: 'quizz', targetEntity: Question::class, orphanRemoval: true)]
    private Collection $questions;

    #[ORM\OneToMany(mappedBy: 'quizz', targetEntity: QuizzEffectue::class, orphanRemoval: true)]
    private Collection $quizzEffectues;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->quizzEffectues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    public function getDifficulte(): ?int
    {
        return $this->difficulte;
    }

    public function setDifficulte(int $difficulte): self
    {
        $this->difficulte = $difficulte;

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setQuizz($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getQuizz() === $this) {
                $question->setQuizz(null);
            }
        }

        return $this;
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
            $quizzEffectue->setQuizz($this);
        }

        return $this;
    }

    public function removeQuizzEffectue(QuizzEffectue $quizzEffectue): self
    {
        if ($this->quizzEffectues->removeElement($quizzEffectue)) {
            // set the owning side to null (unless already changed)
            if ($quizzEffectue->getQuizz() === $this) {
                $quizzEffectue->setQuizz(null);
            }
        }

        return $this;
    }
}
