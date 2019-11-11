<?php


namespace App\Entity\Merch;


class ProductSearch
{
    /**
     * @var int|null
     */
    private $maxPrice;

    /**
     * @var boolean|null
     */
    private $inStock;

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


}