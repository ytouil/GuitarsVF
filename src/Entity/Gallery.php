<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
#[Vich\Uploadable]
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

    #[ORM\OneToMany(mappedBy: 'gallery', targetEntity: Guitar::class)]
    private $guitars;

    #[Vich\UploadableField(mapping: 'gallery_images', fileNameProperty: 'imageName')]
    private ?File $image = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

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
    public function getImage(): ?File
    {
        return $this->image;
    }

    public function setImage(?File $image = null): void
    {
        $this->image = $image;
        if (null !== $image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
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
