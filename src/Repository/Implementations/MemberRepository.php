<?php

namespace App\Repository\Implementations;

use App\Entity\Member;
use App\Entity\Inventory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Interfaces\MemberRepositoryInterface;
use Doctrine\ORM\ORMException;

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
        // Assuming you're simply returning the result without additional logic
        return parent::findAll();
    }
    public function save(Member $member): void
    {
        $this->_em->beginTransaction();
        try {
            // Persist the Inventory first
            $this->_em->persist($member->getInventory());
            
            // Then, persist the Member; the Inventory is already set in the Member's constructor
            $this->_em->persist($member);
           
            
            
            $this->_em->commit();
        } catch (\Throwable $e) {
            $this->_em->rollback();
            // Log the exception details
            error_log($e->getMessage());
            error_log($e->getTraceAsString());
            // Optionally, rethrow the exception to handle it further up the call stack
            throw new \RuntimeException("Unable to save member data: " . $e->getMessage(), 0, $e);
        }
    }


    public function findByEmail(string $email): ?Member
    {
        // Assuming you're simply returning the result without additional logic
        return $this->findOneBy(['email' => $email]);
    }
}
