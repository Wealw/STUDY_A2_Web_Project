<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
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
    private $productName;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $productPrice;

    /**
     * @ORM\Column(type="integer")
     */
    private $productInventory;

    /**
     * @ORM\Column(type="text")
     */
    private $productDescription;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $productImagePath;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ProductType", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $productType;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Command", inversedBy="products")
     */
    private $command;

    public function __construct()
    {
        $this->command = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getProductPrice(): ?string
    {
        return $this->productPrice;
    }

    public function setProductPrice(string $productPrice): self
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    public function getProductInventory(): ?int
    {
        return $this->productInventory;
    }

    public function setProductInventory(int $productInventory): self
    {
        $this->productInventory = $productInventory;

        return $this;
    }

    public function getProductDescription(): ?string
    {
        return $this->productDescription;
    }

    public function setProductDescription(string $productDescription): self
    {
        $this->productDescription = $productDescription;

        return $this;
    }

    public function getProductImagePath(): ?string
    {
        return $this->productImagePath;
    }

    public function setProductImagePath(string $productImagePath): self
    {
        $this->productImagePath = $productImagePath;

        return $this;
    }

    public function getProductType(): ?ProductType
    {
        return $this->productType;
    }

    public function setProductType(?ProductType $productType): self
    {
        $this->productType = $productType;

        return $this;
    }

    /**
     * @return Collection|Command[]
     */
    public function getCommand(): Collection
    {
        return $this->command;
    }

    public function addCommand(Command $command): self
    {
        if (!$this->command->contains($command)) {
            $this->command[] = $command;
        }

        return $this;
    }

    public function removeCommand(Command $command): self
    {
        if ($this->command->contains($command)) {
            $this->command->removeElement($command);
        }

        return $this;
    }
}
