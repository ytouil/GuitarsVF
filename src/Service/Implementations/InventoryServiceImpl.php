<?php

namespace App\Service\Implementations;

use App\Entity\Inventory;
use App\Entity\Member;
use App\Entity\User;
use App\Repository\Interfaces\InventoryRepositoryInterface;
use App\Repository\Interfaces\MemberRepositoryInterface;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Service\Interfaces\InventoryServiceInterface;
use App\Service\Interfaces\MemberServiceInterface;
use App\Service\Interfaces\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class InventoryServiceImpl implements InventoryServiceInterface
{
    private $inventoryRepo;


    public function __construct(InventoryRepositoryInterface $inventoryRepo)
    {
        $this->inventoryRepo = $inventoryRepo;
    }


    public function save(Inventory $inventory) : Inventory
    {
            $this->inventoryRepo->save($inventory);
            return $inventory;

    }
}
