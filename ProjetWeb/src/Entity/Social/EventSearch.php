<?php

namespace App\Entity\Social;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

class EventSearch
{

    /**
     * @var int|null
     * @Assert\Range(min="5", max="100")
     */
    private $maxPrice;

    /**
     * @var EventType|null
     */
    private $category;

    /**
     * @return int|null
     */
    public function getMaxPrice(): ?int
    {
        return $this->maxPrice;
    }

    /**
     * @param int|null $maxPrice
     * @return EventSearch
     */
    public function setMaxPrice(?int $maxPrice): EventSearch
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }

    /**
     * @return EventType|null
     */
    public function getCategory(): ?EventType
    {
        return $this->category;
    }

    /**
     * @param EventType|null $categories
     * @return EventSearch
     */
    public function setCategory(?EventType $categories): EventSearch
    {
        $this->category = $categories;
        return $this;
    }

}