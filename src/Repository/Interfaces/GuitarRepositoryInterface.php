<?php

namespace App\Repository\Interfaces;

use App\Entity\Guitar;

interface GuitarRepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null): ?Guitar;
    public function findAll();
}
