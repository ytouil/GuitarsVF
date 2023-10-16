<?php

namespace App\Repository\Interfaces;

use App\Entity\Message;

interface MessageRepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null): ?Message;
    public function findAll();
}


