<?php

namespace App\Repository\Interfaces;

use App\Entity\Comment;

interface CommentRepositoryInterface
{
    public function find(int $id): ?Comment;

    public function findAll(): array;
}

