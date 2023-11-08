<?php

namespace App\Service\Implementations;

use App\Entity\Member;
use App\Repository\Interfaces\MemberRepositoryInterface;
use App\Service\Interfaces\MemberServiceInterface;

class MemberService implements MemberServiceInterface
{
    private $memberRepository;

    public function __construct(MemberRepositoryInterface $memberRepository)
    {
        $this->memberRepository = $memberRepository;
    }

    public function registerMember(array $data): Member
    {
        $member = new Member();
        $member->setEmail($data['email']);
        $member->setFullName($data['full_name']);
        $member->setBio($data['bio'] ?? null);
        $member->setImage($data['image'] ?? null);
        $member->setRoles(['ROLE_USER']);
        // Hash the password using PHP's password_hash() function
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $member->setPassword($hashedPassword);

        $this->memberRepository->save($member);

        return $member;
    }

    public function authenticateMember(string $email, string $plainPassword): ?Member
    {
        $member = $this->memberRepository->findByEmail($email);

        if ($member && password_verify($plainPassword, $member->getPassword())) {
            return $member;
        }

        return null;
    }
}
