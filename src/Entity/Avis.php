<?php

namespace App\Entity;

use App\Repository\AvisRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Article;

#[ORM\Entity(repositoryClass: AvisRepository::class)]
class Avis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column]
    private ?int $Note = null;

    #[ORM\ManyToOne(inversedBy: 'Avis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Article $soumettre = null;

    #[ORM\ManyToOne(inversedBy: 'Avis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $mettre = null;
    private $article;

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->Note;
    }

    public function setNote(int $Note): static
    {
        $this->Note = $Note;

        return $this;
    }

    public function getSoumettre(): ?Article
    {
        return $this->soumettre;
    }

    public function setSoumettre(?Article $soumettre): static
    {
        $this->soumettre = $soumettre;

        return $this;
    }

    public function getMettre(): ?Utilisateur
    {
        return $this->mettre;
    }

    public function setMettre(?Utilisateur $mettre): static
    {
        $this->mettre = $mettre;

        return $this;
    }
}
