<?php

namespace App\Repository\Implementations;

use App\Entity\Guitar;
use App\Entity\Member;
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

    public function findByUser(Member $member)
    {
        return $this->createQueryBuilder('g')
            ->join('g.inventory', 'i')
            ->where('i.member = :member')
            ->setParameter('member', $member)
            ->getQuery()
            ->getResult();
    }
}