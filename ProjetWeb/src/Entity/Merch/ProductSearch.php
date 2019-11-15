<?php


namespace App\Entity\Merch;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

class ProductSearch
{
    /**
     * @var int|null
     * @Assert\Range(min=10)
     */
    private $maxPrice;

    /**
     * @var boolean|null
     */
    private $inStock;

    /**
     * @var ProductType|null
     */
    private $type;

    /**
     * @param bool|null $inStock
     * @return ProductSearch
     */
    public function setInStock(bool $inStock): ProductSearch
    {
        $this->inStock = $inStock;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getInStock(): ?bool
    {
        return $this->inStock;
    }

    /**
     * @param int|null $maxPrice
     * @return ProductSearch
     */
    public function setMaxPrice(int $maxPrice): ProductSearch
    {
        $this->maxPrice = $maxPrice;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMaxPrice(): ?int
    {
        return $this->maxPrice;
    }

    /**
     * @return ProductType|null
     */
    public function getType(): ?ProductType
    {
        return $this->type;
    }

    /**
     * @param ProductType|null $type
     * @return ProductSearch
     */
    public function setType(?ProductType $type): ProductSearch
    {
        $this->type = $type;
        return $this;
    }


}