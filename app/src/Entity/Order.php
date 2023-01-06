<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
#[ORM\HasLifecycleCallbacks()]
#[UniqueEntity(fields: ['id'], message: 'There is already an order with this id')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $number;

    #[ORM\Column(type: 'integer', length: 255, nullable: true)]
    private int $total;

    #[ORM\Column(type: 'integer', length: 255, nullable: true)]
    private int $status;

    #[ORM\Column(type: 'integer', length: 255, nullable: true)]
    private int $total_installment;

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

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @param string $number
     * @return Order
     */
    public function setNumber(string $number): Order
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @param int $total
     * @return Order
     */
    public function setTotal(int $total): Order
    {
        $this->total = $total;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return Order
     */
    public function setStatus(int $status): Order
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalInstallment(): int
    {
        return $this->total_installment;
    }

    /**
     * @param int $total_installment
     * @return Order
     */
    public function setTotalInstallment(int $total_installment): Order
    {
        $this->total_installment = $total_installment;
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
     * @return Order
     */
    public function setCreatedAt(?\DateTimeInterface $created_at): Order
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
     * @return Order
     */
    public function setModifiedAt(?\DateTimeInterface $modified_at): Order
    {
        $this->modified_at = $modified_at;
        return $this;
    }


}
