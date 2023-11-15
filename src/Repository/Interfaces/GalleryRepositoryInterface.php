<?php

namespace App\Repository\Interfaces;

use App\Entity\Gallery;
use App\Entity\Member;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;

interface GalleryRepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null): ?Gallery;
    public function findAll();
    public function findByUser(User $user);


}


