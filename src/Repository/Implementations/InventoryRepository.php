<?php

namespace App\Repository\Implementations;

use App\Entity\Inventory;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Interfaces\InventoryRepositoryInterface;

class InventoryRepository extends ServiceEntityRepository implements InventoryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inventory::class);
    }

    public function find($id, $lockMode = null, $lockVersion = null): ?Inventory
    {
        return parent::find($id, $lockMode, $lockVersion);
    }

    public function findAll(): array
    {
        return parent::findAll();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findByUser(User $user)
    {
        return $this->createQueryBuilder('i')
            ->where('i.member = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }
}