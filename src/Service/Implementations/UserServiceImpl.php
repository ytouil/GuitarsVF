<?php

namespace App\Service\Implementations;

use App\Entity\Inventory;
use App\Entity\Member;
use App\Entity\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Service\Interfaces\InventoryServiceInterface;
use App\Service\Interfaces\MemberServiceInterface;
use App\Service\Interfaces\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserServiceImpl implements UserServiceInterface
{
    private $userRepository;

    private $memberService;

    private $inventoryService;


    public function __construct(
        UserRepositoryInterface $userRepository,
        MemberServiceInterface $memberService,
        InventoryServiceInterface $inventoryService)
    {
        $this->userRepository = $userRepository;
        $this->memberService = $memberService;
        $this->inventoryService=$inventoryService;
    }


    public function registerMember(array $data): User
    {
        $emailExist = $this->userRepository->findByEmail($data['email']);
        if ($emailExist) {
            throw new \Exception("The email address already exists.");
        }
        // Create User
        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword(password_hash($data['password'], PASSWORD_DEFAULT));
        $user->setRoles(['ROLE_USER']);
        // Create Inventory and associate with Member
        $inventory = new Inventory();
        $inventory->setName('Default Inventory');
        // Create Member and associate with User
        $member = new Member();
        $member->setFullName($data['full_name']);
        $member->setUser($user); // Associate User with Member
        $member->setInventory($inventory);
        $inventory->setMember($member);
        // Persist entities
        $this->userRepository->save($user);
        $this->memberService->save($member);
        $this->inventoryService->save($inventory);

        return $user;
    }

    public function authenticateMember(string $email, string $plainPassword): ?User
    {
        $user = $this->userRepository->findByEmail($email);

        if ($user && password_verify($plainPassword, $user->getPassword())) {

            return $user;
        }

        return null;
    }


}
