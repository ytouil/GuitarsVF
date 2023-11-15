<?php

namespace App\Service\Interfaces;

use App\Entity\Member;

interface MemberServiceInterface
{
    public function save(Member $member);


}
