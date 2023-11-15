<?php

namespace App\Service\Implementations;

use App\Entity\Inventory;
use App\Entity\Member;
use App\Entity\User;
use App\Repository\Interfaces\MemberRepositoryInterface;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Service\Interfaces\MemberServiceInterface;
use App\Service\Interfaces\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class MemberServiceImpl implements MemberServiceInterface
{
    private $memberRepo;


    public function __construct(MemberRepositoryInterface $memberRepo)
    {
        $this->memberRepo = $memberRepo;
    }


    public function save(Member $member) : Member
    {
        $this->memberRepo->save($member);
        return $member;
    }
}
