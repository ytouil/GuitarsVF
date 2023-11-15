<?php

namespace App\Repository\Interfaces;

use App\Entity\Guitar;
use App\Entity\User;

interface GuitarRepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null): ?Guitar;
    public function findAll();

    public function findByUser(User $user);
}
