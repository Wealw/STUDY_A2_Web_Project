<?php

namespace App\Entity\Social;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $comment_text;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     */
    private $comment_posted_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime
     */
    private $comment_modified_at;

    /**
     * @ORM\Column(type="integer")
     */
    private $comment_user_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Social\Picture", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $picture;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Social\Impression", inversedBy="comments")
     */
    private $impression;

    public function __construct()
    {
        $this->impression = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentText(): ?string
    {
        return $this->comment_text;
    }

    public function setCommentText(string $comment_text): self
    {
        $this->comment_text = $comment_text;

        return $this;
    }

    public function getCommentPostedAt(): ?\DateTimeInterface
    {
        return $this->comment_posted_at;
    }

    public function setCommentPostedAt(\DateTimeInterface $comment_posted_at): self
    {
        $this->comment_posted_at = $comment_posted_at;

        return $this;
    }

    public function getCommentModifiedAt(): ?\DateTimeInterface
    {
        return $this->comment_modified_at;
    }

    public function setCommentModifiedAt(?\DateTimeInterface $comment_modified_at): self
    {
        $this->comment_modified_at = $comment_modified_at;

        return $this;
    }

    public function getCommentUserId(): ?int
    {
        return $this->comment_user_id;
    }

    public function setCommentUserId(int $comment_user_id): self
    {
        $this->comment_user_id = $comment_user_id;

        return $this;
    }

    public function getPicture(): ?Picture
    {
        return $this->picture;
    }

    public function setPicture(?Picture $picture): self
    {
        $this->picture = $picture;

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

}
