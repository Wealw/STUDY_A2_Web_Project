<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandRepository")
 */
class Command
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $commandOrderedAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $commandUserId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommandOrderedAt(): ?\DateTimeInterface
    {
        return $this->commandOrderedAt;
    }

    public function setCommandOrderedAt(\DateTimeInterface $commandOrderedAt): self
    {
        $this->commandOrderedAt = $commandOrderedAt;

        return $this;
    }

    public function getCommandUserId(): ?int
    {
        return $this->commandUserId;
    }

    public function setCommandUserId(int $commandUserId): self
    {
        $this->commandUserId = $commandUserId;

        return $this;
    }
}
