<?php

namespace App\Repository\Interfaces;

use App\Entity\Gallery;

interface GalleryRepositoryInterface
{
    public function find(int $id): ?Gallery;

    public function findAll(): array;
}

