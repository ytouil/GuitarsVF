<?php

namespace App\Controller\Security;

use App\Form\Auth\LoginFormType;
use App\Form\Auth\RegistrationFormType;
use App\Service\Interfaces\UserServiceInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Serializer\SerializerInterface;


class UsersAuthController extends AbstractController
{
    private SerializerInterface $serializer;
    private UserServiceInterface $memberService;
    private $authorizationChecker;
    private JWTTokenManagerInterface $JWTTokenManager;

    public function __construct(
        JWTTokenManagerInterface      $JWTTokenManager,
        SerializerInterface           $serializer,
        UserServiceInterface          $memberService,
        AuthorizationCheckerInterface $authorizationChecker) {
        $this->memberService = $memberService;
        $this->serializer = $serializer;
        $this->authorizationChecker = $authorizationChecker;
        $this->JWTTokenManager = $JWTTokenManager;
    }

    #[Route('/register', name: 'show_registration_form', methods: ['GET'])]
    public function showRegistrationForm(): Response {
        $form = $this->createForm(RegistrationFormType::class);

        return $this->render('auth/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/login', name: 'show_login_form', methods: ['GET'])]
    public function showLoginForm(): Response {
        $form = $this->createForm(LoginFormType::class);

        return $this->render('auth/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/register', name: 'register_member', methods: ['POST'])]
    public function register(Request $request): Response {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                return $this->processValidForm($form);
        } else {
            return $this->handleFormErrors($form);
        }
    }

    #[Route('/api/login', name: 'authenticate_member', methods: ['POST'])]
    public function authenticate(Request $request): Response {

    $form = $this->createForm(LoginFormType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        return $this->processValidLoginForm($form);
    } else {
        return $this->handleFormErrors($form);
    }
    }


private function processValidForm($form): Response {
    $data = $form->getData();
    try {
        $this->memberService->registerMember($data);
        return $this->redirectToRoute('show_login_form');
    } catch (\Exception $e) {
        $this->addFlash('error', $e->getMessage());
        return $this->redirectToRoute('show_registration_form');
    }
}

private function processValidLoginForm($form): Response{

    $data = $form->getData();
    $member = $this->memberService->authenticateMember($data['email'], $data['password']);
    if ($member) {
        $jwtToken = $this->JWTTokenManager->create($member);
        $cookie = new Cookie(
            'JWT',               // Cookie name
            $jwtToken,           // Cookie value
            time() + 3600,       // Expiry (1 hour)
            '/',                 // Path
            null,                // Domain
            false,               // Secure (set to true if you're using HTTPS)
            true,                // HttpOnly
            false,               // Raw
            'lax'                // SameSite
        );

        $response = $this->determineRedirectResponseBasedOnRole($member);
        $response->headers->setCookie($cookie);
        return $response;

    } else {
        $this->addFlash('danger', 'Email or password are incorrect');
        return $this->redirectToRoute('show_login_form');
    }
}

    private function determineRedirectResponseBasedOnRole($member): Response
    {
        // Assuming you have a method to get the role of the member
        $role = $member->getRoles();
        if (in_array('ROLE_ADMIN', $role)) {
            return $this->redirectToRoute('admin');
        } elseif (in_array('ROLE_USER', $role)) {
            return $this->redirectToRoute('user_dashboard');
        }

        // Default redirect if role is not matched
        return $this->redirectToRoute('home_app');
    }


private function handleFormErrors($form): Response {
    $errors = array_map(function ($error) {
        return $error->getMessage();
    }, iterator_to_array($form->getErrors(true)));

    return $this->json(['message' => 'Invalid form data', 'errors' => $errors], Response::HTTP_BAD_REQUEST);
}




}
