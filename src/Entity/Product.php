<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

#[ORM\MappedSuperclass]
abstract class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(length: 255)]
    protected ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $picture = null;

    #[ORM\Column]
    protected ?float $price = null;

    #[ORM\Column(length: 20)]
    protected ?string $type = null; // Ajout du champ type

    #[ORM\Column(type: 'datetime_immutable')]
    protected ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'integer')]
    protected int $views = 0;

    #[ORM\Column(type: 'integer')]
    protected int $sales = 0;

        public function __construct(string $type)
    {
        $this->type = $type;
        $this->createdAt = new \DateTimeImmutable(); // Optionnel si tu veux l'initialiser ici
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    protected function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function setViews(int $views): static
    {
        $this->views = $views;
        return $this;
    }
    public function getViews(): int
    {
        return $this->views;
    }

    public function incrementViews(): static
    {
        $this->views++;
        return $this;
    }

    public function getSales(): int
    {
        return $this->sales;
    }

    public function setSales(int $sales): static
{
    $this->sales = $sales;
    return $this;
}

    public function incrementSales(): static
    {
        $this->sales++;
        return $this;
    }
}
