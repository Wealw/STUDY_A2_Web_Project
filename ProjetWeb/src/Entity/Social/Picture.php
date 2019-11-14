<?php

namespace App\Entity\Social;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PictureRepository")
 */
class Picture
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $picture_name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $picture_description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $picture_posted_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $picture_modified_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $picture_path;

    /**
     * @ORM\Column(type="integer")
     */
    private $picture_user_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Social\Event", inversedBy="pictures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Social\Comment", mappedBy="picture", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Social\Impression", inversedBy="pictures")
     */
    private $impression;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_visible;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->impression = new ArrayCollection();

        $this
            ->setPicturePostedAt(new \DateTime())
            ->setIsVisible(1);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPictureName(): ?string
    {
        return $this->picture_name;
    }

    public function setPictureName(?string $picture_name): self
    {
        $this->picture_name = $picture_name;

        return $this;
    }

    public function getPictureDescription(): ?string
    {
        return $this->picture_description;
    }

    public function setPictureDescription(?string $picture_description): self
    {
        $this->picture_description = $picture_description;

        return $this;
    }

    public function getPicturePostedAt(): ?\DateTimeInterface
    {
        return $this->picture_posted_at;
    }

    public function setPicturePostedAt(\DateTimeInterface $picture_posted_at): self
    {
        $this->picture_posted_at = $picture_posted_at;

        return $this;
    }

    public function getPictureModifiedAt(): ?\DateTimeInterface
    {
        return $this->picture_modified_at;
    }

    public function setPictureModifiedAt(?\DateTimeInterface $picture_modified_at): self
    {
        $this->picture_modified_at = $picture_modified_at;

        return $this;
    }

    public function getPicturePath(): ?string
    {
        return $this->picture_path;
    }

    public function setPicturePath(string $picture_path): self
    {
        $this->picture_path = $picture_path;

        return $this;
    }

    public function getPictureUserId(): ?int
    {
        return $this->picture_user_id;
    }

    public function setPictureUserId(int $picture_user_id): self
    {
        $this->picture_user_id = $picture_user_id;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event_id): self
    {
        $this->event = $event_id;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPicture($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getPicture() === $this) {
                $comment->setPicture(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Impression[]
     */
    public function getImpression(): Collection
    {
        return $this->impression;
    }

    public function addImpression(Impression $impression): self
    {
        if (!$this->impression->contains($impression)) {
            $this->impression[] = $impression;
        }

        return $this;
    }

    public function removeImpression(Impression $impression): self
    {
        if ($this->impression->contains($impression)) {
            $this->impression->removeElement($impression);
        }

        return $this;
    }

    public function getIsVisible(): ?bool
    {
        return $this->is_visible;
    }

    public function setIsVisible(bool $is_visible): self
    {
        $this->is_visible = $is_visible;

        return $this;
    }
}
