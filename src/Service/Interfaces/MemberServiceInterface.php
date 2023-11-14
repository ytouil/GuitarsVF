<?php

namespace App\Service\Interfaces;

use App\Entity\Member;

interface MemberServiceInterface
{
    public function registerMember(array $data): Member;

    public function authenticateMember(string $email, string $plainPassword): ?Member;

}
