<?php

namespace App\Controller\User;

use App\Entity\Gallery;
use App\Entity\Guitar;
use App\Entity\User;
use App\Form\GuitarType;
use App\Repository\Interfaces\GalleryRepositoryInterface;
use App\Repository\Interfaces\GuitarRepositoryInterface;
use App\Repository\Interfaces\InventoryRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard/guitar')]
class GuitarCrudController extends AbstractController
{
    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;

    }

    #[Route('/', name: 'app_guitar_index', methods: ['GET'])]
    public function index(GuitarRepositoryInterface $guitarRepository,GalleryRepositoryInterface $galleryRepo): Response
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Not logged in.');
        }
        $guitars = $guitarRepository->findByUser($user);
        $gallery = $galleryRepo->findByUser($user);
        return $this->render('member_guitar/index.html.twig', [
            'guitars' => $guitars,
            'galleries' => $gallery,
        ]);
    }

    #[Route('/new', name: 'app_guitar_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,InventoryRepositoryInterface $inv): Response
    {
        $guitar = new Guitar();
        $form = $this->createForm(GuitarType::class, $guitar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            if (!$user instanceof User) {
                throw new \LogicException('User must be authenticated to create a guitar.');
            }

            $inventory = $inv->findByUser($user);
            if (!$inventory) {
                throw new \LogicException('Inventory Not Found');
            } else {
                $guitar->setInventory($inventory);
            }
            $entityManager->persist($guitar);
            $entityManager->flush();

            return $this->redirectToRoute('app_guitar_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('member_guitar/new.html.twig', [
            'guitar' => $guitar,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_guitar_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Guitar $guitar, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GuitarType::class, $guitar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_guitar_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('member_guitar/edit.html.twig', [
            'guitar' => $guitar,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_guitar_delete', methods: ['POST'])]
    public function delete(Request $request, Guitar $guitar, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$guitar->getId(), $request->request->get('_token'))) {
            $entityManager->remove($guitar);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_guitar_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/add-guitar/{id}', name: 'add_guitar_to_gallery', methods: ['POST'])]
    public function addGuitarToGallery(Request $request,int $id, EntityManagerInterface $entityManager): Response
    {
        $guitar = $entityManager->getRepository(Guitar::class)->find($id);

        if (!$guitar) {
            throw $this->createNotFoundException('Guitar not found.');
        }

        $galleryId = $request->request->get('galleryId');

        if ($galleryId) {
            $gallery = $entityManager->getRepository(Gallery::class)->find($galleryId);
            if (!$gallery) {
                throw $this->createNotFoundException('Gallery not found.');
            }
            $guitar->setGallery($gallery);
        } else {
            $guitar->setGallery(null);
        }

        $entityManager->persist($guitar);
        $entityManager->flush();

        return $this->redirectToRoute('app_guitar_index');
    }


}
