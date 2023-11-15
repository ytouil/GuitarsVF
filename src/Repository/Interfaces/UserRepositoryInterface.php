<?php

namespace App\Repository\Interfaces;

use App\Entity\User;

interface UserRepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null): ?User;
    public function findAll(): array;
    
    // Added method signature for saving a member
    public function save(User $user): void;

    // Added method signature for finding a member by email
    public function findByEmail(string $email): ?User;
}
