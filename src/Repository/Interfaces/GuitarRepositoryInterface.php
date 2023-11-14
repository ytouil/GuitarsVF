<?php

namespace App\Repository\Interfaces;

use App\Entity\Guitar;
use App\Entity\Member;

interface GuitarRepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null): ?Guitar;
    public function findAll();

    public function findByUser(Member $member);
}
