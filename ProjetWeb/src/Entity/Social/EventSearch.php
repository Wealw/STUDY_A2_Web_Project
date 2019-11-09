<?php

namespace App\Entity\Social;

use Symfony\Component\Validator\Constraints as Assert;

class EventSearch
{

    /**
     * @var int|null
     * @Assert\Range(min="5", max="100")
     */
    private $maxPrice;

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

}