<?php
namespace App\Repository\Interfaces;

use App\Entity\Inventory;
use App\Entity\Member;

interface InventoryRepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null): ?Inventory;
    public function findAll();

    public function findByUser(Member $user);
}
