<?php

namespace App\Controller;

use App\Entity\Member;
use App\Form\Type\LoginFormType;
use App\Form\Type\RegistrationFormType;
use App\Service\Interfaces\MemberServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[Route('/member')]
class MemberController extends AbstractController
{
    private SerializerInterface $serializer;
    private MemberServiceInterface $memberService;
    private $authorizationChecker;

    public function __construct( 
        SerializerInterface $serializer,
        MemberServiceInterface $memberService,
        AuthorizationCheckerInterface $authorizationChecker) {
        $this->memberService = $memberService;
        $this->serializer = $serializer;
        $this->authorizationChecker = $authorizationChecker;
    }

    #[Route('/register', name: 'show_registration_form', methods: ['GET'])]
    public function showRegistrationForm(): Response
    {
        $form = $this->createForm(RegistrationFormType::class);

        return $this->render('register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/login', name: 'show_login_form', methods: ['GET'])]
    public function showLoginForm(): Response
    {
        $form = $this->createForm(LoginFormType::class);

        return $this->render('login.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/register', name: 'register_member', methods: ['POST'])]
    public function register(Request $request): Response
    {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
    
            try {
                $member = $this->memberService->registerMember($data);
                $this->addFlash('success', 'Registration successful. You can now login.');
                $context = [
                    AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                        return $object->getId();
                    }
                ];
                
                $jsonMember = $this->serializer->serialize($member, 'json', $context);
    
                return $this->redirectToRoute('show_login_form');
            } catch (\Exception $e) {
                return $this->json(['message' => 'Registration failed', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }
            return $this->json(['message' => 'Invalid form data', 'errors' => $errors], Response::HTTP_BAD_REQUEST);
        }
    }
    
    
    #[Route('/authenticate', name: 'authenticate_member', methods: ['POST'])]
    public function authenticate(Request $request): Response {

    $form = $this->createForm(LoginFormType::class);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();
        $member = $this->memberService->authenticateMember($data['email'], $data['password']);

        if ($member) {
            $context = [AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getId();
                }];
            $jsonMember = $this->serializer->serialize($member, 'json', $context);
             return $this->redirectToRoute('admin');
        } else {
            $this->addFlash('danger', 'Email or password are incorrect');
        return $this->redirectToRoute('show_login_form');
        }
    } else {
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }
        return $this->json(['message' => 'Invalid form data', 'errors' => $errors], Response::HTTP_BAD_REQUEST);
    }
}



    
}
