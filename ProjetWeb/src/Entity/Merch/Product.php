<?php

namespace App\Entity\Merch;

use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Social\Event;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @Vich\Uploadable()
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
     * @var File|null
     * @Vich\UploadableField(mapping="product_image", fileNameProperty="productImagePath")
     */
    private $imageFile;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Merch\ProductType", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $productType;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Merch\CommandProduct", mappedBy="product", orphanRemoval=true)
     */
    private $commandProducts;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isOrderable;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $productModifiedAt;

    public function __construct()
    {
        $this->commandProducts = new ArrayCollection();
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
            $commandProduct->setProduct($this);
        }

        return $this;
    }

    public function removeCommandProduct(CommandProduct $commandProduct): self
    {
        if ($this->commandProducts->contains($commandProduct)) {
            $this->commandProducts->removeElement($commandProduct);
            // set the owning side to null (unless already changed)
            if ($commandProduct->getProduct() === $this) {
                $commandProduct->setProduct(null);
            }
        }

        return $this;
    }

    public function getIsOrderable(): ?bool
    {
        return $this->isOrderable;
    }

    public function setIsOrderable(bool $isOrderable): self
    {
        $this->isOrderable = $isOrderable;

        return $this;
    }

    /**
     * @param File|null $imageFile
     * @return Product
     * @throws \Exception
     */
    public function setImageFile(?File $imageFile): Product
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->productModifiedAt = new \DateTime('now');
        }
        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getProductModifiedAt(): ?\DateTimeInterface
    {
        return $this->productModifiedAt;
    }

    public function setProductModifiedAt(?\DateTimeInterface $productModifiedAt): self
    {
        $this->productModifiedAt = $productModifiedAt;

        return $this;
    }

}
