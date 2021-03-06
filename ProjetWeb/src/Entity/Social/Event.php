<?php

namespace App\Entity\Social;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 * @Vich\Uploadable()
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
     * @var File|null
     * @Vich\UploadableField(mapping="event_image", fileNameProperty="event_image_path")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(min="3", max="50")
     */
    private $event_name;

    /**
     * @ORM\Column(type="text")
     */
    private $event_description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min="3", max="255")
     */
    private $event_image_path;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(min="3", max="255")
     */
    private $event_location;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Range(min="0", max="100")
     */
    private $event_price;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     */
    private $event_date;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     */
    private $event_created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $event_modified_at;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive
     */
    private $event_created_by;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\PositiveOrZero
     */
    private $event_is_visible;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $event_period;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Social\EventType", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event_type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Social\Picture", mappedBy="event", orphanRemoval=true)
     */
    private $pictures;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Social\Participation", mappedBy="event", orphanRemoval=true)
     */
    private $participation;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Social\Impression", inversedBy="events")
     */
    private $impression;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
        $this->participation = new ArrayCollection();
        $this->impression = new ArrayCollection();
        $this->event_created_at = new \DateTime();
        $this->event_created_by = 1;
        $this->event_is_visible = 1;
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
            $picture->setEvent($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->contains($picture)) {
            $this->pictures->removeElement($picture);
            // set the owning side to null (unless already changed)
            if ($picture->getEvent() === $this) {
                $picture->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Participation[]
     */
    public function getParticipation(): Collection
    {
        return $this->participation;
    }

    public function addParticipation(Participation $event): self
    {
        if (!$this->participation->contains($event)) {
            $this->participation[] = $event;
            $event->setEvent($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $event): self
    {
        if ($this->participation->contains($event)) {
            $this->participation->removeElement($event);
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

    public function getEventPeriod(): ?string
    {
        return $this->event_period;
    }

    public function setEventPeriod(?string $event_period): self
    {
        $this->event_period = $event_period;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     * @return Event
     * @throws \Exception
     */
    public function setImageFile(?File $imageFile): Event
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->event_modified_at = new \DateTime('now');
        }
        return $this;
    }



}
