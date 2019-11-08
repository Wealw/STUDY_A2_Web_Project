<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $event_name;

    /**
     * @ORM\Column(type="text")
     */
    private $event_description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $event_image_path;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $event_location;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $event_price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $event_date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $event_created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $event_modified_at;

    /**
     * @ORM\Column(type="integer")
     */
    private $event_created_by;

    /**
     * @ORM\Column(type="boolean")
     */
    private $event_is_visible;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EventType", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event_type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Picture", mappedBy="event_id", orphanRemoval=true)
     */
    private $pictures;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Participation", mappedBy="event", orphanRemoval=true)
     */
    private $event;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Impression", inversedBy="events")
     */
    private $impression;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $event_periode;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
        $this->event = new ArrayCollection();
        $this->impression = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventName(): ?string
    {
        return $this->event_name;
    }

    public function setEventName(string $event_name): self
    {
        $this->event_name = $event_name;

        return $this;
    }

    public function getEventDescription(): ?string
    {
        return $this->event_description;
    }

    public function setEventDescription(string $event_description): self
    {
        $this->event_description = $event_description;

        return $this;
    }

    public function getEventImagePath(): ?string
    {
        return $this->event_image_path;
    }

    public function setEventImagePath(string $event_image_path): self
    {
        $this->event_image_path = $event_image_path;

        return $this;
    }

    public function getEventLocation(): ?string
    {
        return $this->event_location;
    }

    public function setEventLocation(string $event_location): self
    {
        $this->event_location = $event_location;

        return $this;
    }

    public function getEventPrice(): ?int
    {
        return $this->event_price;
    }

    public function setEventPrice(?int $event_price): self
    {
        $this->event_price = $event_price;

        return $this;
    }

    public function getEventDate(): ?\DateTimeInterface
    {
        return $this->event_date;
    }

    public function setEventDate(\DateTimeInterface $event_date): self
    {
        $this->event_date = $event_date;

        return $this;
    }

    public function getEventCreatedAt(): ?\DateTimeInterface
    {
        return $this->event_created_at;
    }

    public function setEventCreatedAt(\DateTimeInterface $event_created_at): self
    {
        $this->event_created_at = $event_created_at;

        return $this;
    }

    public function getEventModifiedAt(): ?\DateTimeInterface
    {
        return $this->event_modified_at;
    }

    public function setEventModifiedAt(?\DateTimeInterface $event_modified_at): self
    {
        $this->event_modified_at = $event_modified_at;

        return $this;
    }

    public function getEventCreatedBy(): ?int
    {
        return $this->event_created_by;
    }

    public function setEventCreatedBy(int $event_created_by): self
    {
        $this->event_created_by = $event_created_by;

        return $this;
    }

    public function getEventIsVisible(): ?bool
    {
        return $this->event_is_visible;
    }

    public function setEventIsVisible(bool $event_is_visible): self
    {
        $this->event_is_visible = $event_is_visible;

        return $this;
    }

    public function getEventType(): ?EventType
    {
        return $this->event_type;
    }

    public function setEventType(?EventType $event_type_id): self
    {
        $this->event_type = $event_type_id;

        return $this;
    }

    /**
     * @return Collection|Picture[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setEventId($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->contains($picture)) {
            $this->pictures->removeElement($picture);
            // set the owning side to null (unless already changed)
            if ($picture->getEventId() === $this) {
                $picture->setEventId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Participation[]
     */
    public function getEvent(): Collection
    {
        return $this->event;
    }

    public function addEvent(Participation $event): self
    {
        if (!$this->event->contains($event)) {
            $this->event[] = $event;
            $event->setEvent($this);
        }

        return $this;
    }

    public function removeEvent(Participation $event): self
    {
        if ($this->event->contains($event)) {
            $this->event->removeElement($event);
            // set the owning side to null (unless already changed)
            if ($event->getEvent() === $this) {
                $event->setEvent(null);
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

    public function getEventPeriode(): ?string
    {
        return $this->event_periode;
    }

    public function setEventPeriode(?string $event_periode): self
    {
        $this->event_periode = $event_periode;

        return $this;
    }

}
