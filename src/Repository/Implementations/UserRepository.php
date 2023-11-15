<?php

namespace App\Repository\Implementations;

use App\Entity\Member;
use App\Entity\User;
use App\Entity\Inventory;
use App\Repository\Interfaces\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function find($id, $lockMode = null, $lockVersion = null): ?User
    {
        // Assuming you're simply returning the result without additional logic
        return parent::find($id, $lockMode, $lockVersion);
    }

    public function findAll(): array
    {
        // Assuming you're simply returning the result without additional logic
        return parent::findAll();
    }
    public function save(User $user): void
    {
        $this->_em->beginTransaction();
        try {
            $this->_em->persist($user);
            if ($member = $user->getMember()) {
                $this->_em->persist($member);
                if ($inventory = $member->getInventory()) {
                    $this->_em->persist($inventory);
                }
            }

            $this->_em->flush();
            $this->_em->commit();
        } catch (\Throwable $e) {
            $this->_em->rollback();
            throw new \RuntimeException("Unable to save user data: " . $e->getMessage(), 0, $e);
        }
    }


    public function findByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }
}
