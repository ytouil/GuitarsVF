<?php
namespace App\Controller;

use App\Entity\Member;
use App\Service\Interfaces\MemberServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/member')]
class MemberController extends AbstractController
{
    public function __construct(
        private MemberServiceInterface $memberService
    ) {}

    #[Route('/register', name: 'show_registration_form', methods: ['GET'])]
    public function showRegistrationForm(): Response
    {
        return $this->render('register.html.twig');  // Assuming you've created this Twig template.
    }

    #[Route('/login', name: 'show_login_form', methods: ['GET'])]
    public function showLoginForm(): Response
    {
        return $this->render('login.html.twig');  // Assuming you've created this Twig template.
    }

    #[Route('/register', name: 'register_member', methods: ['POST'])]
    public function register(Request $request): Response
    {
        // Get data from the form fields
        $data = $request->request->all();
    
        // You can further validate or clean up $data if needed
    
        if (!$data) {
            return $this->json(['message' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        }
    
        try {
            $member = $this->memberService->registerMember($data);
            return $this->json($member, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json(['message' => 'Registration failed', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    #[Route('/authenticate', name: 'authenticate_member', methods: ['POST'])]
    public function authenticate(Request $request): Response
    {
        // Get data from the form fields
        $data = $request->request->all();
    
        if (!isset($data['email']) || !isset($data['password'])) {
            return $this->json(['message' => 'Email and password are required'], Response::HTTP_BAD_REQUEST);
        }
    
        $member = $this->memberService->authenticateMember($data['email'], $data['password']);
    
        if ($member) {
            // This is a simplistic representation. In a real-world scenario, you'd probably generate a JWT or similar token here.
            return $this->json(['message' => 'Authenticated successfully', 'member' => $member]);
        } else {
            return $this->json(['message' => 'Authentication failed'], Response::HTTP_UNAUTHORIZED);
        }
    }
    
}
