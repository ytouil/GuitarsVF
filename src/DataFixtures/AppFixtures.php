<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Gallery;
use App\Entity\Guitar;
use App\Entity\Inventory;
use App\Entity\Member;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $sourceDir = 'public/assets/dummyImages/';
        $targetDir = 'public/assets/uploads/';

        // Create Users
        $admin = $this->createUser('admin@gmail.com', ['ROLE_ADMIN']);
        $johnUser = $this->createUser('john@gmail.com', ['ROLE_USER']);
        $aliceUser = $this->createUser('alice@gmail.com', ['ROLE_USER']);

        $manager->persist($admin);
        $manager->persist($johnUser);
        $manager->persist($aliceUser);

        // Create Members
        $adminMember = $this->createMember('Admin', 'Bio about admin', $admin, '4.webp', $sourceDir, $targetDir);
        $johnMember = $this->createMember('John Doe', 'Bio about John', $johnUser, '7.webp', $sourceDir, $targetDir);
        $aliceMember = $this->createMember('Alice Smith', 'Bio about Alice', $aliceUser, '8.jpg', $sourceDir, $targetDir);

        $manager->persist($adminMember);
        $manager->persist($johnMember);
        $manager->persist($aliceMember);

        // Create Inventory
        $johnInventory = $this->createInventory('John Inventory', $johnMember);
        $aliceInventory = $this->createInventory('Alice Inventory', $aliceMember);

        $manager->persist($johnInventory);
        $manager->persist($aliceInventory);

        // Create Guitars
        $guitar1 = $this->createGuitar('Stratocaster', 'Description about Stratocaster', $johnInventory, '1.webp', $sourceDir, $targetDir);
        $guitar2 = $this->createGuitar('Telecaster', 'Description about Telecaster', $aliceInventory, '2.webp', $sourceDir, $targetDir);

        $manager->persist($guitar1);
        $manager->persist($guitar2);

        // Create Galleries
        $johnGallery = $this->createGallery('John Gallery', 'Gallery belonging to John', $johnMember, [$guitar1], '9.webp', $sourceDir, $targetDir);
        $aliceGallery = $this->createGallery('Alice Gallery', 'Gallery belonging to Alice', $aliceMember, [$guitar2], '10.webp', $sourceDir, $targetDir);

        $manager->persist($johnGallery);
        $manager->persist($aliceGallery);

        // Create Comments
        $comment1 = $this->createComment('Great guitar, John!', $guitar1, $johnMember);
        $comment2 = $this->createComment('Nice guitar, Alice!', $guitar2, $aliceMember);

        $manager->persist($comment1);
        $manager->persist($comment2);

        // Create Messages
        $message1 = $this->createMessage('Hey John, how are you?', $johnMember, $aliceMember);
        $message2 = $this->createMessage('Hey Alice, I am good!', $aliceMember, $johnMember);

        $manager->persist($message1);
        $manager->persist($message2);

        $manager->flush();
    }

    private function createUser($email, $roles)
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword(password_hash('123456', PASSWORD_DEFAULT));
        $user->setRoles($roles);
        return $user;
    }

    private function createMember($fullName, $bio, $user, $imageName, $sourceDir, $targetDir)
    {
        $member = new Member();
        $member->setFullName($fullName);
        $member->setBio($bio);
        $member->setUser($user);
        $this->addImageToFile($member, $imageName, $sourceDir, $targetDir);
        return $member;
    }

    private function createInventory($name, Member $member)
    {
        $inventory = new Inventory();
        $inventory->setName($name);
        $inventory->setMember($member);
        return $inventory;
    }

    private function createGuitar($modelName, $description, Inventory $inventory, $imageName, $sourceDir, $targetDir)
    {
        $guitar = new Guitar();
        $guitar->setModelName($modelName);
        $guitar->setDescription($description);
        $guitar->setInventory($inventory);
        $this->addImageToFile($guitar, $imageName, $sourceDir, $targetDir);
        return $guitar;
    }

    private function createGallery($name, $description, Member $member, array $guitars, $imageName, $sourceDir, $targetDir)
    {
        $gallery = new Gallery();
        $gallery->setName($name);
        $gallery->setDescription($description);
        $gallery->setMember($member);
        foreach ($guitars as $guitar) {
            $gallery->addGuitar($guitar);
        }
        $this->addImageToFile($gallery, $imageName, $sourceDir, $targetDir);
        return $gallery;
    }

    private function createComment($content, Guitar $guitar, Member $member)
    {
        $comment = new Comment();
        $comment->setContent($content);
        $comment->setGuitar($guitar);
        $comment->setMember($member);
        $comment->setTimestamp(new \DateTime());
        return $comment;
    }

    private function createMessage($content, Member $sender, Member $receiver)
    {
        $message = new Message();
        $message->setContent($content);
        $message->setSender($sender);
        $message->setReceiver($receiver);
        $message->setTimestamp(new \DateTime());
        return $message;
    }

    private function addImageToFile($entity, $imageName, $sourceDir, $targetDir)
    {
        if (copy($sourceDir . $imageName, $targetDir . $imageName)) {
            $file = new File($targetDir . $imageName);
            $entity->setImage($file);
            $entity->setImageName($imageName);
        }
    }
}
