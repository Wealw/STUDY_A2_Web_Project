<?php

namespace App\Entity\Merch;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandProductRepository")
 */
class CommandProduct
{
    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\Merch\Command", inversedBy="commandProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $command;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\Merch\Product", inversedBy="commandProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getCommand(): ?Command
    {
        return $this->command;
    }

    public function setCommand(?Command $command): self
    {
        $this->command = $command;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
