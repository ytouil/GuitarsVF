<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Gallery;
use App\Entity\Guitar;
use App\Entity\Inventory;
use App\Entity\Member;
use App\Entity\Message;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Vich\UploaderBundle\Entity\File;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Create members
        $john = $this->createMember('john@example.com', 'John Doe', 'Bio about John', 'App/pictures/Members/john.jpg', '123456');
        $alice = $this->createMember('alice@example.com', 'Alice Smith', 'Bio about Alice', 'App/pictures/Members/alice.jpg', '123456');
        
        $manager->persist($john);
        $manager->persist($alice);

        // Create Inventories
        $johnInventory = $this->createInventory('John Inventory', $john, 'App/pictures/Inventories/john_inventory.jpg');
        $aliceInventory = $this->createInventory('Alice Inventory', $alice, 'App/pictures/Inventories/alice_inventory.jpg');

        $manager->persist($johnInventory);
        $manager->persist($aliceInventory);

        // Create Galleries and associate them with Members
        $johnGallery = $this->createGallery('John Gallery', 'Description about John gallery', ['App/pictures/Guitars/stratocaster.jpg', 'App/pictures/Inventories/john_inventory.jpg'], $john);
        $aliceGallery = $this->createGallery('Alice Gallery', 'Description about Alice gallery', ['App/pictures/Guitars/telecaster.jpg', 'App/pictures/Inventories/alice_inventory.jpg'], $alice);

        $manager->persist($johnGallery);
        $manager->persist($aliceGallery);

        // Create Guitars
        $stratocaster = $this->createGuitar('Stratocaster', 'Description about Stratocaster', $johnInventory, $johnGallery, 'App/pictures/Guitars/stratocaster.jpg');
        $telecaster = $this->createGuitar('Telecaster', 'Description about Telecaster', $aliceInventory, $aliceGallery, 'App/pictures/Guitars/telecaster.jpg');

        $manager->persist($stratocaster);
        $manager->persist($telecaster);

        // Create Comments
        $comment1 = $this->createComment('Great guitar!', new \DateTime(), $stratocaster, $john);
        $comment2 = $this->createComment('I love this one!', new \DateTime(), $telecaster, $alice);

        $manager->persist($comment1);
        $manager->persist($comment2);

        // Create Messages
        $message1 = $this->createMessage('Hello John, your guitar is amazing!', new \DateTime(), $john, $alice);
        $message2 = $this->createMessage('Thanks Alice, appreciate it!', new \DateTime(), $alice, $john);

        $manager->persist($message1);
        $manager->persist($message2);

        $manager->flush();
    }

    private function createMember(string $email, string $fullName, string $bio, string $imagePath, string $password): Member
    {
        $member = new Member();
        $member->setEmail($email);
        $member->setFullName($fullName);
        $member->setBio($bio);
        $member->setImage($imagePath);
        $member->setPassword($password);

        return $member;
    }

    private function createInventory(string $name, Member $member, string $imagePath): Inventory
    {
        $inventory = new Inventory();
        $inventory->setName($name);
        $inventory->setMember($member);
        $inventory->setImage($imagePath);

        return $inventory;
    }

    private function createGallery(string $name, string $description, array $imagePaths, Member $member): Gallery
    {
        $gallery = new Gallery();
        $gallery->setName($name);
        $gallery->setDescription($description);
        foreach ($imagePaths as $path) {
            $gallery->addImage($path);
        }
        $gallery->setMember($member);

        return $gallery;
    }

    private function createGuitar(string $modelName, string $description, Inventory $inventory, Gallery $gallery, string $imagePath): Guitar
    {
        $guitar = new Guitar();
        $guitar->setModelName($modelName);
        $guitar->setDescription($description);
        $guitar->setInventory($inventory);
        $guitar->setGallery($gallery);
        if (file_exists($imagePath)) {
            $file = new File($imagePath);
            $guitar->setImage($file);
        }

        return $guitar;
    }

    private function createComment(string $content, \DateTimeInterface $timestamp, Guitar $guitar, Member $member): Comment
    {
        $comment = new Comment();
        $comment->setContent($content);
        $comment->setTimestamp($timestamp);
        $comment->setGuitar($guitar);
        $comment->setMember($member);

        return $comment;
    }

    private function createMessage(string $content, \DateTimeInterface $timestamp, Member $sender, Member $receiver): Message
    {
        $message = new Message();
        $message->setContent($content);
        $message->setTimestamp($timestamp);
        $message->setSender($sender);
        $message->setReceiver($receiver);

        return $message;
    }
}
