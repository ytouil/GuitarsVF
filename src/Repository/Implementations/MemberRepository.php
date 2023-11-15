<?php

namespace App\Repository\Implementations;

use App\Entity\Member;
use App\Entity\User;
use App\Repository\Interfaces\MemberRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MemberRepository extends ServiceEntityRepository implements MemberRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Member::class);
    }

    public function find($id, $lockMode = null, $lockVersion = null): ?Member
    {
        // Assuming you're simply returning the result without additional logic
        return parent::find($id, $lockMode, $lockVersion);
    }

    public function findAll(): array
    {
        return parent::findAll();
    }
    public function save(Member $member): void
    {
        $this->_em->beginTransaction();
        try {
            $this->_em->persist($member);
            $this->_em->flush();
            $this->_em->commit();
        } catch (\Throwable $e) {
            $this->_em->rollback();
            throw new \RuntimeException("Unable to save user data: " . $e->getMessage(), 0, $e);
        }
    }


    public function findByEmail(string $email): ?Member
    {
        return $this->findOneBy(['email' => $email]);
    }
}
