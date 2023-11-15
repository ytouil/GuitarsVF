<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[Entity(repositoryClass: 'App\Repository\Implementations\InventoryRepository')]
#[Table(name: 'inventory')]
class Inventory
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: 'integer')]
    private int $id;

    #[Column(type: 'string', length: 255)]
    private string $name;

    #[OneToOne(inversedBy: 'inventory', targetEntity: Member::class)]
    #[JoinColumn(name: 'member_id', referencedColumnName: 'id', nullable: false)]
    private $member;

    #[OneToMany(mappedBy: 'inventory', targetEntity: Guitar::class)]
    private $guitars;

    public function __construct()
    {
        $this->guitars = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getMember(): Member
    {
        return $this->member;
    }

    public function setMember(Member $member): self
    {
        $this->member = $member;
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
            $guitar->setInventory($this);
        }

        return $this;
    }

    public function removeGuitar(Guitar $guitar): self
    {
        if ($this->guitars->contains($guitar)) {
            $this->guitars->removeElement($guitar);
            if ($guitar->getInventory() === $this) {
                $guitar->setInventory(null);
            }
        }

        return $this;
    }
}
