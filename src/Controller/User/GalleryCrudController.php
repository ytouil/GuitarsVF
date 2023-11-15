<?php

namespace App\Controller\User;

use App\Entity\Gallery;
use App\Entity\User;
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
    private $galleryRepository;

    private $entityManager;

    public function __construct(Security $security,GalleryRepositoryInterface $galleryRepository,EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->galleryRepository=$galleryRepository;
        $this->entityManager=$entityManager;
    }


    #[Route('/', name: 'app_gallery_index', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Not logged in.');
        }
        $gallery = $this->galleryRepository->findByUser($user);

        return $this->render('member_gallery/index.html.twig', [
            'galleries' => $gallery,
        ]);
    }

    #[Route('/new', name: 'app_gallery_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $gallery = new Gallery();
        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->security->getUser();
            if(!$user instanceof User){
                throw $this->createAccessDeniedException("Must Logged In");
            }
            $member = $user->getMember();
            $gallery->setMember($member);
            $this->entityManager->persist($gallery);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_gallery_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('member_gallery/new.html.twig', [
            'gallery' => $gallery,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_gallery_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Gallery $gallery): Response
    {
        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_gallery_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('member_gallery/edit.html.twig', [
            'gallery' => $gallery,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_gallery_delete', methods: ['POST'])]
    public function delete(Request $request, Gallery $gallery): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gallery->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($gallery);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_gallery_index', [], Response::HTTP_SEE_OTHER);
    }
}
