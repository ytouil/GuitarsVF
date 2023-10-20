<?php

namespace App\Controller\Admin;

use App\Entity\Guitar;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class GuitarCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Guitar::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
