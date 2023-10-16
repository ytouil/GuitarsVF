<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: 'App\Repository\MessageRepository')]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'text')]
    private string $content;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $timestamp;

    #[ORM\ManyToOne(targetEntity: Member::class)]
    private $sender;

    #[ORM\ManyToOne(targetEntity: Member::class)]
    private $receiver;

    public function getId(): int
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getTimestamp(): \DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    public function getSender(): Member
    {
        return $this->sender;
    }

    public function setSender(Member $sender): self
    {
        $this->sender = $sender;
        return $this;
    }

    public function getReceiver(): Member
    {
        return $this->receiver;
    }

    public function setReceiver(Member $receiver): self
    {
        $this->receiver = $receiver;
        return $this;
    }
}
