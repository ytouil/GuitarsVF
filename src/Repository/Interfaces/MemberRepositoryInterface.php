<?php

namespace App\Repository\Interfaces;

use App\Entity\Member;

interface MemberRepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null): ?Member;
    public function findAll(): array;
    
    // Added method signature for saving a member
    public function save(Member $member): void;

    // Added method signature for finding a member by email
    public function findByEmail(string $email): ?Member;
}
