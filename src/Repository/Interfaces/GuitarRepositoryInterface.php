<?php

namespace App\Repository\Interfaces;

use App\Entity\Guitar;

interface GuitarRepositoryInterface
{
    public function find(int $id): ?Guitar;

    public function findAll(): array;

}