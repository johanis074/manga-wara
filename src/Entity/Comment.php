<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
    {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'comments')]
    private ?User $user = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Book $book = null;

    #[ORM\ManyToOne(targetEntity: Figurine::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Figurine $figurine = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;
        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }
    public function setBook(?Book $book): static
        {
            if ($book !== null && $this->figurine !== null) {
                throw new \LogicException("Un commentaire ne peut être attaché qu'à un seul produit (Book ou Figurine).");
            }
            $this->book = $book;
            return $this;
        }

    public function getFigurine(): ?Figurine
    {
        return $this->figurine;
    }

    
    
    public function setFigurine(?Figurine $figurine): static
    {
        if ($figurine !== null && $this->book !== null) {
            throw new \LogicException("Un commentaire ne peut être attaché qu'à un seul produit (Book ou Figurine).");
        }
        $this->figurine = $figurine;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;
        return $this;
    }
}

