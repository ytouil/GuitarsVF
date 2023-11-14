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
            // Check if the Member already has an Inventory
            if (null === $member->getInventory()) {
                // If not, create and set a new Inventory
                $inventory = new Inventory();
                $inventory->setName('Default Inventory');
                $inventory->setMember($member);
                $member->setInventory($inventory);

                // Persist the new Inventory
                $this->_em->persist($inventory);
            }

            // Then, persist the Member
            $this->_em->persist($member);
            $this->_em->flush();
            $this->_em->commit();
        } catch (\Throwable $e) {
            $this->_em->rollback();
            // Log and/or handle the exception as before
            throw new \RuntimeException("Unable to save member data: " . $e->getMessage(), 0, $e);
        }
    }


    public function findByEmail(string $email): ?Member
    {
        return $this->findOneBy(['email' => $email]);
    }
}
