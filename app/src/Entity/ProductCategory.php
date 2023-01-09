<?php

namespace App\Entity;

use App\Repository\ProductCategoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ProductCategoryRepository::class)]
#[ORM\Table(name: '`product_category`')]
#[ORM\HasLifecycleCallbacks()]
#[UniqueEntity(fields: ['sku'], message: 'There is already an product with this sku')]
class ProductCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $modified_at;


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

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ProductCategory
     */
    public function setName(?string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return ?string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return ProductCategory
     */
    public function setDescription(?string $description)
    {
        $this->description = $description;
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
     * @return ProductCategory
     */
    public function setCreatedAt(?\DateTimeInterface $created_at): ProductCategory
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
     * @return ProductCategory
     */
    public function setModifiedAt(?\DateTimeInterface $modified_at): ProductCategory
    {
        $this->modified_at = $modified_at;
        return $this;
    }


}
