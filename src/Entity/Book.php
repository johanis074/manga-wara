<?php

namespace App\Entity;

use CategoryManga;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BookRepository;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book extends Product
{
    #[ORM\Column]
    private ?int $reference = null;

    #[ORM\Column]
    private ?int $isbn = null;

    #[ORM\Column]
    private ?int $ean = null;

    #[ORM\Column(length: 255)]
    private ?string $editor = null;

    #[ORM\Column(nullable: true, enumType: CategoryManga::class)]
    private ?CategoryManga $category = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $synopsis = null;

    public function __construct()
    {
        parent::__construct('book');
        $this->editor = 'KANA'; // Valeur par défaut en majuscule
        $this->category = CategoryManga::SHONEN;
    }

    public function getCollectionName(): string
    {
        return preg_replace('/\s*[Tt]ome\s*\d+/', '', $this->name);
    }

    public function getReference(): ?int
    {
        return $this->reference;
    }

    public function setReference(int $reference): static
    {
        $this->reference = $reference;
        return $this;
    }

    public function getIsbn(): ?int
    {
        return $this->isbn;
    }

    public function setIsbn(int $isbn): static
    {
        $this->isbn = $isbn;
        return $this;
    }

    public function getEan(): ?int
    {
        return $this->ean;
    }

    public function setEan(int $ean): static
    {
        $this->ean = $ean;
        return $this;
    }

    public function getEditor(): ?string
    {
        return $this->editor;
    }

    public function setEditor(?string $editor): static
    {
        $this->editor = strtoupper($editor);
        return $this;
    }

    public function getCategory(): ?CategoryManga
    {
        return $this->category;
    }

    public function setCategory(?CategoryManga $category): static
    {
        $this->category = $category;
        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(string $synopsis): static
    {
        $this->synopsis = $synopsis;
        return $this;
    }
}

