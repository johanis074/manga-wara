<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FigurineRepository;

#[ORM\Entity(repositoryClass: FigurineRepository::class)]
class Figurine extends Product
{
    #[ORM\Column(type: "text")]
    private ?string $description = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $reference = null;




    #[ORM\Column]
    private ?float $height = null;

    #[ORM\Column(type: "string", length: 50)]
    private ?string $brand = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'figurine')]
    private Collection $comments;

    public function __construct()
    {
        parent::__construct('figurine'); // Définition du type
        $this->brand = 'BANDAI'; // Valeur par défaut (optionnel)
        $this->comments = new ArrayCollection();
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = mb_strtoupper($description);
        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): static
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

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = mb_strtoupper($brand);
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
