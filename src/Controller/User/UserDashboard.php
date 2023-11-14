<?php


namespace App\Controller\User;

use App\Repository\Interfaces\GuitarRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserDashboard extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/dashboard', name: 'member_profil', methods: ['GET'])]
    public function index()
    {


        return $this->render('member/index.html.twig', [

        ]);
    }
}
