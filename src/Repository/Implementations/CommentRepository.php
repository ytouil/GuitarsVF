<?php

namespace App\Repository\Implementations;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Interfaces\CommentRepositoryInterface;

class CommentRepository extends ServiceEntityRepository implements CommentRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function find($id, $lockMode = null, $lockVersion = null): ?Comment
    {
        return parent::find($id, $lockMode, $lockVersion);
    }
    public function findAll(): array
    {
        return parent::findAll();
    }

}