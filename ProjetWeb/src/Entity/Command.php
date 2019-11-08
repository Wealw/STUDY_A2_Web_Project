<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandRepository")
 */
class Command
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $commandOrderedAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $commandUserId;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", mappedBy="command")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CommandProduct", mappedBy="command", orphanRemoval=true)
     */
    private $commandProducts;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->commandProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommandOrderedAt(): ?\DateTimeInterface
    {
        return $this->commandOrderedAt;
    }

    public function setCommandOrderedAt(\DateTimeInterface $commandOrderedAt): self
    {
        $this->commandOrderedAt = $commandOrderedAt;

        return $this;
    }

    public function getCommandUserId(): ?int
    {
        return $this->commandUserId;
    }

    public function setCommandUserId(int $commandUserId): self
    {
        $this->commandUserId = $commandUserId;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addCommand($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            $product->removeCommand($this);
        }

        return $this;
    }

    /**
     * @return Collection|CommandProduct[]
     */
    public function getCommandProducts(): Collection
    {
        return $this->commandProducts;
    }

    public function addCommandProduct(CommandProduct $commandProduct): self
    {
        if (!$this->commandProducts->contains($commandProduct)) {
            $this->commandProducts[] = $commandProduct;
            $commandProduct->setCommand($this);
        }

        return $this;
    }

    public function removeCommandProduct(CommandProduct $commandProduct): self
    {
        if ($this->commandProducts->contains($commandProduct)) {
            $this->commandProducts->removeElement($commandProduct);
            // set the owning side to null (unless already changed)
            if ($commandProduct->getCommand() === $this) {
                $commandProduct->setCommand(null);
            }
        }

        return $this;
    }
}
