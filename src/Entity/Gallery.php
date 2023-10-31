<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: 'App\Repository\Implementations\GalleryRepository')]
class Gallery
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description;

    #[ORM\OneToMany(targetEntity: Guitar::class, mappedBy: 'gallery')]
    private $guitars;

    // Adding the images attribute
    #[ORM\Column(type: 'array', nullable: true)]
    private array $images = [];
    #[ORM\ManyToOne(targetEntity: Member::class, inversedBy: 'galleries')]
    #[ORM\JoinColumn(nullable: false)]
    private Member $member;

    // Getter and setter for member
    public function getMember(): Member
    {
        return $this->member;
    }

    public function setMember(Member $member): self
    {
        $this->member = $member;
        return $this;
    }

    public function __construct()
    {
        $this->guitars = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getGuitars(): Collection
    {
        return $this->guitars;
    }    

    public function addGuitar(Guitar $guitar): self
    {
        if (!$this->guitars->contains($guitar)) {
            $this->guitars[] = $guitar;
            $guitar->setGallery($this);
        }

        return $this;
    }

    public function removeGuitar(Guitar $guitar): self
    {
        if ($this->guitars->removeElement($guitar) && $guitar->getGallery() === $this) {
            $guitar->setGallery(null);
        }

        return $this;
    }

    // Getters and Setters for the images attribute
    public function getImages(): array
    {
        return $this->images;
    }

    public function addImage(string $image): self
    {
        if (!in_array($image, $this->images, true)) {
            $this->images[] = $image;
        }

        return $this;
    }

    public function removeImage(string $image): self
    {
        $index = array_search($image, $this->images, true);
        if ($index !== false) {
            unset($this->images[$index]);
            $this->images = array_values($this->images);
        }

        return $this;
    }
}
