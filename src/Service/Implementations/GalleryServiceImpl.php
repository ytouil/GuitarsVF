<?php

namespace App\Service\Implementations;

use App\Entity\Gallery;
use App\Entity\Inventory;

use App\Repository\Interfaces\GalleryRepositoryInterface;
use App\Service\Interfaces\GalleryServiceInterface;


class GalleryServiceImpl
{
    private $galleryRepo;


    public function __construct(GalleryRepositoryInterface $galleryRepo)
    {
        $this->galleryRepo = $galleryRepo;
    }

}
