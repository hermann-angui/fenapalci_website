<?php

namespace App\Entity;

use App\Repository\ProductRepository;
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

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $product_pictures;

    #[ORM\ManyToOne(targetEntity: ProductCategory::class, inversedBy: 'category', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductCategory $category = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $created_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $modified_at;


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

    public function getId(): ?int
    {
        return $this->id;
    }
}
