<?php

namespace App\Service\Interfaces;

use App\Entity\Inventory;

interface InventoryServiceInterface
{
    public function save(Inventory $inventory) : Inventory;


}
