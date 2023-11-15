<?php

namespace App\Service\Interfaces;

use App\Entity\User;

interface UserServiceInterface
{
    public function registerMember(array $data): User;

    public function authenticateMember(string $email, string $plainPassword): ?User;

}
