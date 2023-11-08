<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;

#[Entity(repositoryClass: 'App\Repository\Implementations\MemberRepository')]
#[Table(name: 'member')]
class Member
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: 'integer')]
    private int $id;

    #[Column(type: 'string', length: 180, unique: true)]
    private string $email;

    #[Column(type: 'string', length: 255)]
    private string $password;

    #[Column(type: 'string', length: 255)]
    private string $full_name;

    #[Column(type: 'text', nullable: true)]
    private ?string $bio;

    #[OneToOne(targetEntity: Inventory::class, mappedBy: 'member', cascade: ['persist'])]
    private ?Inventory $inventory;    

    #[Column(type: 'json')]
    private array $roles = [];


    // Adding the image attribute
    #[Column(type: 'string', length: 255, nullable: true)]
    private ?string $image = null;

    #[OneToMany(targetEntity: Gallery::class, mappedBy: 'member')]
    private $galleries;

    public function __construct()
    {
        $this->galleries = new ArrayCollection();
        // Create a new Inventory instance by default
        $this->inventory = new Inventory();
        $this->inventory->setName('Default Inventory');
        // Set the back-reference from Inventory to this Member
        $this->inventory->setMember($this);
        $this->roles = ['ROLE_USER'];
    }
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getFullName(): string
    {
        return $this->full_name;
    }

    public function setFullName(string $full_name): self
    {
        $this->full_name = $full_name;
        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;
        return $this;
    }

    public function getInventory(): Inventory
    {
        return $this->inventory;
    }

    public function setInventory(Inventory $inventory): self
    {
        $this->inventory = $inventory;
        return $this;
    }

    // Getter and Setter for the image attribute
    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;
        return $this;
    }
    public function getGalleries(): Collection
    {
        return $this->galleries;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles(), true);
    }

    public function addGallery(Gallery $gallery): self
    {
        if (!$this->galleries->contains($gallery)) {
            $this->galleries[] = $gallery;
            $gallery->setMember($this);
        }

        return $this;
    }

    public function removeGallery(Gallery $gallery): self
    {
        if ($this->galleries->removeElement($gallery) && $gallery->getMember() === $this) {
            $gallery->setMember(null);
        }

        return $this;
    }
}
