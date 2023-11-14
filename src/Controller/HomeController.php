<?php


namespace App\Controller;


use App\Entity\Member;
use App\Repository\Interfaces\GuitarRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;

    }
    #[Route('/', name: 'home_app')]
    public function getAllGuitars(GuitarRepositoryInterface $guitarRepository): Response
    {
        $guitars = $guitarRepository->findAll();
        return $this->render('home.html.twig', [
            'guitars' => $guitars,
        ]);
    }

    #[Route('/single-product', name: 'single_prod')]
    public function getSingleProd(GuitarRepositoryInterface $guitarRepository): Response
    {
        return $this->render('product/show_single_product.html.twig', [

        ]);
    }


}