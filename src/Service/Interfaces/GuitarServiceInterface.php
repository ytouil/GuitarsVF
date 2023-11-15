<?php

namespace App\Service\Interfaces;

use App\Entity\Gallery;
use App\Entity\Guitar;

interface GuitarServiceInterface
{
    public function saveGuitar(Guitar $guitar) : Guitar;


}
