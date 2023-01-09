<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: '`product`')]
#[ORM\HasLifecycleCallbacks()]
#[UniqueEntity(fields: ['sku'], message: 'There is already an product with this sku')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $product_sku;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $product_name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $product_supplier;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $product_description;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $product_unit_price;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $product_unit_in_stock;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $product_sell_price;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $product_supplier_price;

    #[ORM\ManyToOne(targetEntity: ProductCategory::class, inversedBy: 'category', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductCategory $category = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $modified_at;

    #[ORM\Column(nullable: true)]
    private array $product_pictures = [];

    public function __construct()
    {
    }

    /**
     * Prepersist gets triggered on Insert
     * @ORM\PrePersist
     */
    public function updatedTimestamps()
    {
        if ($this->created_at == null) {
            $this->created_at = new \DateTime('now');
        }
        $this->modified_at =  new \DateTime('now');
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Product
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductSku()
    {
        return $this->product_sku;
    }

    /**
     * @param mixed $product_sku
     * @return Product
     */
    public function setProductSku($product_sku)
    {
        $this->product_sku = $product_sku;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductName()
    {
        return $this->product_name;
    }

    /**
     * @param mixed $product_name
     * @return Product
     */
    public function setProductName($product_name)
    {
        $this->product_name = $product_name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductSupplier()
    {
        return $this->product_supplier;
    }

    /**
     * @param mixed $product_supplier
     * @return Product
     */
    public function setProductSupplier($product_supplier)
    {
        $this->product_supplier = $product_supplier;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductDescription()
    {
        return $this->product_description;
    }

    /**
     * @param mixed $product_description
     * @return Product
     */
    public function setProductDescription($product_description)
    {
        $this->product_description = $product_description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductUnitPrice()
    {
        return $this->product_unit_price;
    }

    /**
     * @param mixed $product_unit_price
     * @return Product
     */
    public function setProductUnitPrice($product_unit_price)
    {
        $this->product_unit_price = $product_unit_price;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductUnitInStock()
    {
        return $this->product_unit_in_stock;
    }

    /**
     * @param mixed $product_unit_in_stock
     * @return Product
     */
    public function setProductUnitInStock($product_unit_in_stock)
    {
        $this->product_unit_in_stock = $product_unit_in_stock;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductSellPrice()
    {
        return $this->product_sell_price;
    }

    /**
     * @param mixed $product_sell_price
     * @return Product
     */
    public function setProductSellPrice($product_sell_price)
    {
        $this->product_sell_price = $product_sell_price;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductSupplierPrice()
    {
        return $this->product_supplier_price;
    }

    /**
     * @param mixed $product_supplier_price
     * @return Product
     */
    public function setProductSupplierPrice($product_supplier_price)
    {
        $this->product_supplier_price = $product_supplier_price;
        return $this;
    }

    /**
     * @return ProductCategory|null
     */
    public function getCategory(): ?ProductCategory
    {
        return $this->category;
    }

    /**
     * @param ProductCategory|null $category
     * @return Product
     */
    public function setCategory(?ProductCategory $category): Product
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    /**
     * @param \DateTimeInterface|null $created_at
     * @return Product
     */
    public function setCreatedAt(?\DateTimeInterface $created_at): Product
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getModifiedAt(): ?\DateTimeInterface
    {
        return $this->modified_at;
    }

    /**
     * @param \DateTimeInterface|null $modified_at
     * @return Product
     */
    public function setModifiedAt(?\DateTimeInterface $modified_at): Product
    {
        $this->modified_at = $modified_at;
        return $this;
    }

    public function getProductPictures(): array
    {
        return $this->product_pictures;
    }

    public function setProductPictures(?array $product_pictures): self
    {
        $this->product_pictures = $product_pictures;

        return $this;
    }


}
