<?php

namespace App\Repository\Implementations;

use App\Entity\Gallery;
use App\Entity\Member;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Interfaces\GalleryRepositoryInterface;

class GalleryRepository extends ServiceEntityRepository implements GalleryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gallery::class);
    }

    public function find($id, $lockMode = null, $lockVersion = null): ?Gallery
    {
        return parent::find($id, $lockMode, $lockVersion);
    }

    public function findAll(): array
    {
        return parent::findAll();
    }


    public function findByUser(User $user)
    {
        return $this->createQueryBuilder('g')
            ->where('g.member = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}