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

class AppFixtures extends Fixture
{

public function load(ObjectManager $manager)
{
// Create Users
$johnUser = new User();
$johnUser->setEmail('john@example.com');
$johnUser->setPassword(password_hash('123456', PASSWORD_DEFAULT));
$johnUser->setRoles(['ROLE_ADMIN']);

$aliceUser = new User();
$aliceUser->setEmail('alice@example.com');
    $aliceUser->setPassword(password_hash('123456', PASSWORD_DEFAULT));
$aliceUser->setRoles(['ROLE_USER']);

$manager->persist($johnUser);
$manager->persist($aliceUser);

// Create Members
$johnMember = new Member();
$johnMember->setFullName('John Doe');
$johnMember->setBio('Bio about John');
$johnMember->setUser($johnUser);

$aliceMember = new Member();
$aliceMember->setFullName('Alice Smith');
$aliceMember->setBio('Bio about Alice');
$aliceMember->setUser($aliceUser);

$manager->persist($johnMember);
$manager->persist($aliceMember);

// Create Inventory
$johnInventory = new Inventory();
$johnInventory->setName('John Inventory');
$johnInventory->setMember($johnMember);

$aliceInventory = new Inventory();
$aliceInventory->setName('Alice Inventory');
$aliceInventory->setMember($aliceMember);

$manager->persist($johnInventory);
$manager->persist($aliceInventory);

// Create Guitar
$guitar1 = new Guitar();
$guitar1->setModelName('Stratocaster');
$guitar1->setDescription('Description about Stratocaster');
$guitar1->setInventory($johnInventory);

$guitar2 = new Guitar();
$guitar2->setModelName('Telecaster');
$guitar2->setDescription('Description about Telecaster');
$guitar2->setInventory($aliceInventory);

$manager->persist($guitar1);
$manager->persist($guitar2);

// Create Gallery
$johnGallery = new Gallery();
$johnGallery->setName('John Gallery');
$johnGallery->setDescription('Gallery belonging to John');
$johnGallery->setMember($johnMember);
$johnGallery->addGuitar($guitar1);

$aliceGallery = new Gallery();
$aliceGallery->setName('Alice Gallery');
$aliceGallery->setDescription('Gallery belonging to Alice');
$aliceGallery->setMember($aliceMember);
$aliceGallery->addGuitar($guitar2);

$manager->persist($johnGallery);
$manager->persist($aliceGallery);

// Create Comment
$comment1 = new Comment();
$comment1->setContent('Great guitar, John!');
$comment1->setTimestamp(new \DateTime());
$comment1->setGuitar($guitar1);
$comment1->setMember($johnMember);

$comment2 = new Comment();
$comment2->setContent('Nice guitar, Alice!');
$comment2->setTimestamp(new \DateTime());
$comment2->setGuitar($guitar2);
$comment2->setMember($aliceMember);

$manager->persist($comment1);
$manager->persist($comment2);

// Create Message
$message1 = new Message();
$message1->setContent('Hey John, how are you?');
$message1->setTimestamp(new \DateTime());
$message1->setSender($aliceMember);
$message1->setReceiver($johnMember);

$message2 = new Message();
$message2->setContent('Hey Alice, I am good!');
$message2->setTimestamp(new \DateTime());
$message2->setSender($johnMember);
$message2->setReceiver($aliceMember);

$manager->persist($message1);
$manager->persist($message2);

$manager->flush();
}
}
