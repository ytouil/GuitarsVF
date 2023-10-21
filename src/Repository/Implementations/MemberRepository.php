<?php

namespace App\Repository\Implementations;

use App\Entity\Member;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Interfaces\MemberRepositoryInterface;

class MemberRepository extends ServiceEntityRepository implements MemberRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Member::class);
    }

    public function find($id, $lockMode = null, $lockVersion = null): ?Member
    {
        return parent::find($id, $lockMode, $lockVersion);
    }

    public function findAll(): array
    {
        return parent::findAll();
    }

    // Added method to save a member entity
    public function save(Member $member): void
    {
        $this->_em->persist($member);
        $this->_em->flush();
    }

    // Added method to find a member by email
    public function findByEmail(string $email): ?Member
    {
        return $this->findOneBy(['email' => $email]);
    }
}
