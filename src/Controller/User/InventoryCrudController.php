<?php


namespace App\Controller\User;

use App\Entity\Inventory;
use App\Entity\User;
use App\Form\InventoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/dashboard/inventory')]
class InventoryCrudController extends AbstractController{
    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;

    }

    #[Route('/', name: 'app_inv_index', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Not logged in.');
        }

        return $this->render('member_inv/edit.html.twig', [

        ]);
    }

    #[Route('/{id}', name: 'app_inv_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Inventory $inv, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InventoryType::class, $inv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('user_dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('member_guitar/edit.html.twig', [
            'inv' => $inv,
            'form' => $form,
        ]);
    }

}