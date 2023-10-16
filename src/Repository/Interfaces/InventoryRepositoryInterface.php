<?php

namespace App\Repository;

use App\Entity\Inventory;

interface InventoryRepositoryInterface
{
    public function find(int $id): ?Inventory;

    public function findAll(): array;

}