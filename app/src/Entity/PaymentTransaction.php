<?php

namespace App\Entity;

use App\Repository\PaymentTransactionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentTransactionRepository::class)]
#[ORM\HasLifecycleCallbacks()]
#[ORM\Table(name: '`payment_transaction`')]
class PaymentTransaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'uuid')]
    private string $payment_reference;

    #[ORM\Column(type: 'string', length: 255)]
    private string $checkout_session_id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $amount;

    #[ORM\Column(type: 'string', length: 5, nullable: true)]
    private string $currency;

    #[ORM\Column(type: 'string', length: 5, nullable: true)]
    private string $operator;

    #[ORM\Column(type: 'string', nullable: true)]
    private string $payment_mode;

    #[ORM\Column(type: 'string', nullable: true)]
    private string $payment_for;

    #[ORM\Column(type: 'string', nullable: true)]
    private string $payment_status;

    #[ORM\Column(type: 'string', nullable: true)]
    private string $payment_type;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $payment_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $modified_at;

    #[ORM\ManyToOne(inversedBy: 'paymentTransactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $payer = null;

    #[ORM\Column]
    private ?int $beneficiary = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPayer(): ?User
    {
        return $this->payer;
    }

    public function setPayer(?User $payer): self
    {
        $this->payer = $payer;

        return $this;
    }

    public function getBeneficiary(): ?int
    {
        return $this->beneficiary;
    }

    public function setBeneficiary(?int $beneficiary): self
    {
        $this->beneficiary = $beneficiary;

        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentReference(): string
    {
        return $this->payment_reference;
    }

    /**
     * @param string $payment_reference
     * @return PaymentTransaction
     */
    public function setPaymentReference(?string $payment_reference): PaymentTransaction
    {
        $this->payment_reference = $payment_reference;
        return $this;
    }

    /**
     * @return string
     */
    public function getCheckoutSessionId(): string
    {
        return $this->checkout_session_id;
    }

    /**
     * @param string $checkout_session_id
     * @return PaymentTransaction
     */
    public function setCheckoutSessionId(?string $checkout_session_id): PaymentTransaction
    {
        $this->checkout_session_id = $checkout_session_id;
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
     * @return PaymentTransaction
     */
    public function setAmount(?string $amount): PaymentTransaction
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return PaymentTransaction
     */
    public function setCurrency(?string $currency): PaymentTransaction
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentMode(): string
    {
        return $this->payment_mode;
    }

    /**
     * @param string $payment_mode
     * @return PaymentTransaction
     */
    public function setPaymentMode(?string $payment_mode): PaymentTransaction
    {
        $this->payment_mode = $payment_mode;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentFor(): string
    {
        return $this->payment_for;
    }

    /**
     * @param string $payment_for
     * @return PaymentTransaction
     */
    public function setPaymentFor(?string $payment_for): PaymentTransaction
    {
        $this->payment_for = $payment_for;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentStatus(): string
    {
        return $this->payment_status;
    }

    /**
     * @param string $payment_status
     * @return PaymentTransaction
     */
    public function setPaymentStatus(string $payment_status): PaymentTransaction
    {
        $this->payment_status = $payment_status;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentType(): string
    {
        return $this->payment_type;
    }

    /**
     * @param string $payment_type
     * @return PaymentTransaction
     */
    public function setPaymentType(string $payment_type): PaymentTransaction
    {
        $this->payment_type = $payment_type;
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
     * @return PaymentTransaction
     */
    public function setPaymentDate(?\DateTimeInterface $payment_date): PaymentTransaction
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
     * @return PaymentTransaction
     */
    public function setCreatedAt(?\DateTimeInterface $created_at): PaymentTransaction
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
     * @return PaymentTransaction
     */
    public function setModifiedAt(?\DateTimeInterface $modified_at): PaymentTransaction
    {
        $this->modified_at = $modified_at;
        return $this;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @param string $operator
     * @return PaymentTransaction
     */
    public function setOperator(?string $operator): PaymentTransaction
    {
        $this->operator = $operator;
        return $this;
    }



}
