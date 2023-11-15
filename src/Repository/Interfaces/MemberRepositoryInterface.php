<?php

namespace App\Repository\Interfaces;

use App\Entity\Member;

interface MemberRepositoryInterface
{
    public function save(Member $member): void;
}


