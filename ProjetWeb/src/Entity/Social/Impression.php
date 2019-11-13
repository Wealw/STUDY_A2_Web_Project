<?php

namespace App\Entity\Social;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImpressionRepository")
 */
class Impression
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $impression_user_id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $impression_type;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Social\Event", mappedBy="impression")
     */
    private $events;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Social\Comment", mappedBy="impression")
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Social\Picture", mappedBy="impression")
     */
    private $pictures;

    /**
     * Impression constructor.
     */
    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->pictures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImpressionUserId(): ?int
    {
        return $this->impression_user_id;
    }

    public function setImpressionUserId(int $impression_user_id): self
    {
        $this->impression_user_id = $impression_user_id;

        return $this;
    }

    public function getImpressionType(): ?string
    {
        return $this->impression_type;
    }

    public function setImpressionType(string $impression_type): self
    {
        $this->impression_type = $impression_type;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->addImpression($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            $event->removeImpression($this);
        }

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->addImpression($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            $comment->removeImpression($this);
        }

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->addImpression($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->contains($picture)) {
            $this->pictures->removeElement($picture);
            $picture->removeImpression($this);
        }

        return $this;
    }

}
