<?php


namespace App\Repository\Interfaces;

use App\Entity\Comment;

interface CommentRepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null);

    public function findAll();

}

