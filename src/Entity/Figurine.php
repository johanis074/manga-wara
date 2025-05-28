<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Enum\Brand;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FigurineRepository;

#[ORM\Entity(repositoryClass: FigurineRepository::class)]
class Figurine extends Product
{


    #[ORM\Column(type: "text")]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $reference = null;

    #[ORM\Column]
    private ?float $height = null;

    #[ORM\Column(enumType: Brand::class)]
    private ?Brand $brand = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'figurine')]
    private Collection $comments;

    public function __construct()
    {
        parent::__construct('figurine'); // Définition du type
        $this->brand = Brand::BANDAI; // Valeur par défaut
        $this->comments = new ArrayCollection();
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

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(Brand $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setFigurine($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getFigurine() === $this) {
                $comment->setFigurine(null);
            }
        }

        return $this;
    }
}
