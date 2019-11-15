<?php

namespace App\Entity\Social;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParticipationRepository")
 */
class Participation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive
     */
    private $participation_user_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Social\Event", inversedBy="participation")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParticipationUserId(): ?int
    {
        return $this->participation_user_id;
    }

    public function setParticipationUserId(int $participation_user_id): self
    {
        $this->participation_user_id = $participation_user_id;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }
}
