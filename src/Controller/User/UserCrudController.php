<?php

namespace App\Controller\User;

use App\Entity\Member;
use App\Form\MemberType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile')]
class UserCrudController extends AbstractController
{

    #[Route('', name: 'user_profile', methods: ['GET'])]
    public function getUserProfile(): Response
    {
        return $this->render('member/profile.html.twig', [
        ]);
    }
    #[Route('/{id}/edit', name: 'app_member_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to edit your profile.');
        }

        $member = $user->getMember();
        if (!$member) {
            throw $this->createAccessDeniedException('Not Allowed');
        }

        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->has('email')) {
                $email = $form->get('email')->getData();
                $user->setEmail($email);
            }

            // Flush changes for both Member and User
            $entityManager->flush();

            return $this->redirectToRoute('user_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('member_crud/edit.html.twig', [
            'member' => $member,
            'form' => $form,
        ]);
    }

}
