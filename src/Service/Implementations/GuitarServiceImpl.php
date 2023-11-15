<?php

namespace App\Service\Implementations;

use App\Entity\Gallery;
use App\Entity\Guitar;
use App\Entity\Inventory;

use App\Repository\Interfaces\GalleryRepositoryInterface;
use App\Repository\Interfaces\GuitarRepositoryInterface;
use App\Service\Interfaces\GalleryServiceInterface;
use App\Service\Interfaces\GuitarServiceInterface;


class GuitarServiceImpl
{
    private $guitarRepo;


    public function __construct(GuitarRepositoryInterface $guitarRepo)
    {
        $this->guitarRepo = $guitarRepo;
    }
}
