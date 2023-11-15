<?php


namespace App\Controller\User;

use App\Repository\Interfaces\GuitarRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
#[IsGranted('ROLE_USER')]
class UserDashboardController extends AbstractController
{

    #[Route('/dashboard', name: 'user_dashboard', methods: ['GET'])]
    public function index()
    {
        return $this->render('member/index.html.twig', [

        ]);
    }


}
