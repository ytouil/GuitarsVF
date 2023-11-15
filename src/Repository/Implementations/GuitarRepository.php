<?php

namespace App\Repository\Implementations;

use App\Entity\Guitar;
use App\Entity\Member;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Interfaces\GuitarRepositoryInterface;

class GuitarRepository extends ServiceEntityRepository implements GuitarRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Guitar::class);
    }

    public function find($id, $lockMode = null, $lockVersion = null): ?Guitar
    {
        return parent::find($id, $lockMode, $lockVersion);
    }

    public function findAll(): array
    {
        return parent::findAll();
    }

    public function findByUser(User $user)
    {
        return $this->createQueryBuilder('g') // 'g' alias for Guitar
        ->join('g.inventory', 'i') // Join Guitar to Inventory
        ->join('i.member', 'm') // Join Inventory to Member
        ->join('m.user', 'u') // Join Member to User
        ->where('u.id = :user') // Filter by User ID
        ->setParameter('user', $user) // Pass the specific User object
        ->getQuery()
            ->getResult();
    }
}