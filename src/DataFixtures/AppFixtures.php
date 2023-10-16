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

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Create members
        $john = $this->createMember('john@example.com', 'John Doe', 'Bio about John');
        $alice = $this->createMember('alice@example.com', 'Alice Smith', 'Bio about Alice');
        
        $manager->persist($john);
        $manager->persist($alice);

        // Create Inventories
        $johnInventory = $this->createInventory('John Inventory', $john);
        $aliceInventory = $this->createInventory('Alice Inventory', $alice);

        $manager->persist($johnInventory);
        $manager->persist($aliceInventory);

        // Create Galleries
        $johnGallery = $this->createGallery('John Gallery', 'Description about John gallery');
        $aliceGallery = $this->createGallery('Alice Gallery', 'Description about Alice gallery');

        $manager->persist($johnGallery);
        $manager->persist($aliceGallery);

        // Create Guitars
        $stratocaster = $this->createGuitar('Stratocaster', 'Description about Stratocaster', $johnInventory, $johnGallery);
        $telecaster = $this->createGuitar('Telecaster', 'Description about Telecaster', $aliceInventory, $aliceGallery);

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

    private function createMember(string $email, string $fullName, string $bio): Member
    {
        $member = new Member();
        $member->setEmail($email);
        $member->setFullName($fullName);
        $member->setBio($bio);

        return $member;
    }

    private function createInventory(string $name, Member $member): Inventory
    {
        $inventory = new Inventory();
        $inventory->setName($name);
        $inventory->setMember($member);

        return $inventory;
    }

    private function createGallery(string $name, string $description): Gallery
    {
        $gallery = new Gallery();
        $gallery->setName($name);
        $gallery->setDescription($description);

        return $gallery;
    }

    private function createGuitar(string $modelName, string $description, Inventory $inventory, Gallery $gallery): Guitar
    {
        $guitar = new Guitar();
        $guitar->setModelName($modelName);
        $guitar->setDescription($description);
        $guitar->setInventory($inventory);
        $guitar->setGallery($gallery);

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
