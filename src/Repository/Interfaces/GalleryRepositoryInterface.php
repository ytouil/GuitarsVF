<?php

namespace App\Repository\Interfaces;

use App\Entity\Gallery;

interface GalleryRepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null): ?Gallery;
    public function findAll();
}


