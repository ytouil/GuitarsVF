<?php

namespace App\Repository\Interfaces;

use App\Entity\Message;

interface MessageRepositoryInterface
{
    public function find(int $id): ?Message;

    public function findAll(): array;
}

