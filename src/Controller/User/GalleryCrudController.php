<?php

namespace App\Controller\User;

use App\Entity\Gallery;
use App\Entity\Member;
use App\Form\GalleryType;
use App\Repository\Interfaces\GalleryRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/gallery')]
class GalleryCrudController extends AbstractController
{
    private $security;


    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    #[Route('/', name: 'app_gallery_index', methods: ['GET'])]
    public function index(GalleryRepositoryInterface $galleryRepository): Response
    {
        $user = $this->security->getUser();
        if (!$user instanceof Member) {
            throw $this->createAccessDeniedException('Not logged in.');
        }
        $gallery = $galleryRepository->findByUser($user);

        return $this->render('member_gallery/index.html.twig', [
            'galleries' => $gallery,
        ]);
    }

    #[Route('/new', name: 'app_gallery_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $gallery = new Gallery();
        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($gallery);
            $entityManager->flush();

            return $this->redirectToRoute('app_gallery_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('member_gallery/new.html.twig', [
            'gallery' => $gallery,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_gallery_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Gallery $gallery, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_gallery_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('member_gallery/edit.html.twig', [
            'gallery' => $gallery,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gallery_delete', methods: ['POST'])]
    public function delete(Request $request, Gallery $gallery, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gallery->getId(), $request->request->get('_token'))) {
            $entityManager->remove($gallery);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_gallery_index', [], Response::HTTP_SEE_OTHER);
    }
}
