<?php

namespace App\Entity;

use App\Repository\BookRepository;
use CategoryManga;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Editor;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $picture = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    

    #[ORM\Column]
    private ?float $price = null;

    

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
        $this->editor = Editor::KANA; // Définir une valeur par défaut
        $this->category = CategoryManga::SHONEN; // Définir une valeur par défaut
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
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

    public function setSynopsis(string $sysnopsis): static
    {
        $this->synopsis = $sysnopsis;

        return $this;
    }
}
