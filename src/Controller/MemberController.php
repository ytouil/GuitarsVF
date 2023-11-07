<?php

namespace App\Controller;

use App\Entity\Member;
use App\Service\Interfaces\MemberServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\HttpFoundation\Response;

#[Route('/member')]
class MemberController extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(
        private MemberServiceInterface $memberService,
        SerializerInterface $serializer // Inject the Serializer service
    ) {
        $this->serializer = $serializer;
    }

    #[Route('/register', name: 'show_registration_form', methods: ['GET'])]
    public function showRegistrationForm(): Response
    {
        return $this->render('register.html.twig'); 
    }

    #[Route('/login', name: 'show_login_form', methods: ['GET'])]
    public function showLoginForm(): Response
    {
        return $this->render('login.html.twig');  
    }

    #[Route('/register', name: 'register_member', methods: ['POST'])]
    public function register(Request $request): Response
    {
        $data = $request->request->all();
    
        if (!$data) {
            return $this->json(['message' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        }
    
        try {
            $member = $this->memberService->registerMember($data);
            
            // Use the serializer to handle the circular reference
            $context = [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getId();
                }
            ];

            $jsonMember = $this->serializer->serialize($member, 'json', $context);

            return new JsonResponse($jsonMember, Response::HTTP_CREATED, [], true);
        } catch (\Exception $e) {
            return $this->json(['message' => 'Registration failed', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    #[Route('/authenticate', name: 'authenticate_member', methods: ['POST'])]
    public function authenticate(Request $request): Response
    {
        $data = $request->request->all();
    
        if (!isset($data['email']) || !isset($data['password'])) {
            return $this->json(['message' => 'Email and password are required'], Response::HTTP_BAD_REQUEST);
        }
    
        $member = $this->memberService->authenticateMember($data['email'], $data['password']);
    
        if ($member) {
            // Use the serializer to handle the circular reference
            $context = [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                    return $object->getId();
                }
            ];

            $jsonMember = $this->serializer->serialize($member, 'json', $context);
            return new JsonResponse($jsonMember, Response::HTTP_OK, [], true);
        } else {
            return $this->json(['message' => 'Authentication failed'], Response::HTTP_UNAUTHORIZED);
        }
    }
}
