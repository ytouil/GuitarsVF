<?php

namespace App\Service\Interfaces;

use App\Entity\Gallery;

interface GalleryServiceInterface
{
    public function saveInventory(Gallery $gallery) : Gallery;


}
