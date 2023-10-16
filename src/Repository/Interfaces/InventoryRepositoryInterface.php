<?php
namespace App\Repository\Interfaces;

use App\Entity\Inventory;

interface InventoryRepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null): ?Inventory;
    public function findAll();
}
