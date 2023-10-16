<?php

namespace App\Repository\Interfaces;

use App\Entity\Member;

interface MemberRepositoryInterface
{
    public function find(int $id): ?Member;

    public function findAll(): array;
}