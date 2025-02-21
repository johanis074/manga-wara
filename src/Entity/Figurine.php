<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FigurineRepository;

#[ORM\Entity(repositoryClass: FigurineRepository::class)]
class Figurine extends Product
{
    #[ORM\Column(length: 255)]
    private ?string $brand = null;

    #[ORM\Column(type: "text")]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $reference = null;

    #[ORM\Column]
    private ?float $height = null;

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
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

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(float $height): static
    {
        $this->height = $height;
        return $this;
    }
}
