<?php

namespace App\Entity;

use Editor;
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

    #[ORM\Column(nullable: true, enumType: Editor::class)]
    private ?Editor $editor = null;

    #[ORM\Column(nullable: true, enumType: CategoryManga::class)]
    private ?CategoryManga $category = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $synopsis = null;

    public function __construct()
    {
        parent::__construct('book'); // Définition du type
        $this->editor = Editor::KANA; // Valeur par défaut
        $this->category = CategoryManga::SHONEN; // Valeur par défaut
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

    public function getEditor(): ?Editor
    {
        return $this->editor;
    }

    public function setEditor(?Editor $editor): static
    {
        $this->editor = $editor;
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
