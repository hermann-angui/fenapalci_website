<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: '`digital_asset`')]
#[ORM\HasLifecycleCallbacks()]
#[UniqueEntity(fields: ['id'], message: 'There is already an product with this id')]
class DigitalAsset
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $path;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $description;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $supplier;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $modified_at;

    #[ORM\ManyToOne(inversedBy: 'digitalAssets')]
    private ?Product $product = null;

    public function __construct()
    {
        $this->created_at = new \DateTime('now');
        $this->modified_at = new \DateTime('now');
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
     * @return string
     */
    public function getSku(): ?string
    {
        return $this->sku;
    }

    /**
     * @param mixed $sku
     * @return Product
     */
    public function setSku(?string $sku)
    {
        $this->sku = $sku;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Product
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * @param string $psupplier
     * @return Product
     */
    public function setSupplier(string $supplier)
    {
        $this->supplier = $supplier;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Product
     */
    public function setDescription(?string $description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return int
     */
    public function getUnitPrice() :? int
    {
        return $this->unit_price;
    }

    /**
     * @param int $unit_price
     * @return Product
     */
    public function setUnitPrice(int $unit_price)
    {
        $this->unit_price = $unit_price;
        return $this;
    }

    /**
     * @return int
     */
    public function getUnitInStock(): ?int
    {
        return $this->unit_in_stock;
    }

    /**
     * @param int $unit_in_stock
     * @return Product
     */
    public function setUnitInStock(?int $unit_in_stock)
    {
        $this->unit_in_stock = $unit_in_stock;
        return $this;
    }

    /**
     * @return int
     */
    public function getSellPrice()
    {
        return $this->sell_price;
    }

    /**
     * @param int $sell_price
     * @return Product
     */
    public function setSellPrice(int $sell_price)
    {
        $this->sell_price = $sell_price;
        return $this;
    }

    /**
     * @return int
     */
    public function getSupplierPrice()
    {
        return $this->supplier_price;
    }

    /**
     * @param int $supplier_price
     * @return Product
     */
    public function setSupplierPrice(int $supplier_price)
    {
        $this->supplier_price = $supplier_price;
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

    /**
     * @return mixed
     */
    public function getMarque() : ?string
    {
        return $this->marque;
    }

    /**
     * @param string $marque
     * @return Product
     */
    public function setMarque(?string $marque)
    {
        $this->marque = $marque;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return DigitalAsset
     */
    public function setPath(string $path): DigitalAsset
    {
        $this->path = $path;
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
