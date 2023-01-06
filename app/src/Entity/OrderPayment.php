<?php

namespace App\Entity;

use App\Repository\OrderPaymentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: OrderPaymentRepository::class)]
#[ORM\Table(name: '`order_payment`')]
#[ORM\HasLifecycleCallbacks()]
#[UniqueEntity(fields: ['id'], message: 'There is already an order with this id')]
class OrderPayment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $number;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $amount;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $tax;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $commission;

    #[ORM\Column(type: 'integer', nullable: true)]
    private int $penality;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $installment_date;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $payment_date;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $modified_at;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'order', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $order = null;

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
     * @return OrderPayment
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
     * @return OrderPayment
     */
    public function setNumber(string $number): OrderPayment
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     * @return OrderPayment
     */
    public function setAmount(string $amount): OrderPayment
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return string
     */
    public function getTax(): string
    {
        return $this->tax;
    }

    /**
     * @param string $tax
     * @return OrderPayment
     */
    public function setTax(string $tax): OrderPayment
    {
        $this->tax = $tax;
        return $this;
    }

    /**
     * @return string
     */
    public function getCommission(): string
    {
        return $this->commission;
    }

    /**
     * @param string $commission
     * @return OrderPayment
     */
    public function setCommission(string $commission): OrderPayment
    {
        $this->commission = $commission;
        return $this;
    }

    /**
     * @return int
     */
    public function getPenality(): int
    {
        return $this->penality;
    }

    /**
     * @param int $penality
     * @return OrderPayment
     */
    public function setPenality(int $penality): OrderPayment
    {
        $this->penality = $penality;
        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getInstallmentDate(): ?\DateTimeInterface
    {
        return $this->installment_date;
    }

    /**
     * @param \DateTimeInterface|null $installment_date
     * @return OrderPayment
     */
    public function setInstallmentDate(?\DateTimeInterface $installment_date): OrderPayment
    {
        $this->installment_date = $installment_date;
        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getPaymentDate(): ?\DateTimeInterface
    {
        return $this->payment_date;
    }

    /**
     * @param \DateTimeInterface|null $payment_date
     * @return OrderPayment
     */
    public function setPaymentDate(?\DateTimeInterface $payment_date): OrderPayment
    {
        $this->payment_date = $payment_date;
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
     * @return OrderPayment
     */
    public function setCreatedAt(?\DateTimeInterface $created_at): OrderPayment
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
     * @return OrderPayment
     */
    public function setModifiedAt(?\DateTimeInterface $modified_at): OrderPayment
    {
        $this->modified_at = $modified_at;
        return $this;
    }

    /**
     * @return Order|null
     */
    public function getOrder(): ?Order
    {
        return $this->order;
    }

    /**
     * @param Order|null $order
     * @return OrderPayment
     */
    public function setOrder(?Order $order): OrderPayment
    {
        $this->order = $order;
        return $this;
    }


}
