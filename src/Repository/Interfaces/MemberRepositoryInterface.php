<?php

namespace App\Repository\Interfaces;

use App\Entity\Member;

interface MemberRepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null): ?Member;
    public function findAll();
}
