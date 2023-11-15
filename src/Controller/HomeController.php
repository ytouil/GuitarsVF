<?php


namespace App\Controller;


use App\Entity\Member;
use App\Repository\Interfaces\GalleryRepositoryInterface;
use App\Repository\Interfaces\GuitarRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController{

    private $security;
    private $guitarRepository;
    private $galleryRepo;

    public function __construct(Security $security,GuitarRepositoryInterface $guitarRepository,GalleryRepositoryInterface $galleryRepo)
    {
        $this->security = $security;
        $this->guitarRepository=$guitarRepository;
        $this->galleryRepo=$galleryRepo;

    }
    #[Route('/', name: 'home_app')]
    public function getAllGuitars(): Response
    {
        $guitars = $this->guitarRepository->findAll();
        $galleries = $this->galleryRepo->findAll();
        return $this->render('home.html.twig', [
            'guitars' => $guitars,
            'galleries' =>$galleries,
        ]);
    }

    #[Route('/single-product', name: 'single_prod')]
    public function getSingleProd(): Response
    {
        return $this->render('product/show_single_product.html.twig', [

        ]);
    }


}