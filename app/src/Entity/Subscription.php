<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
#[ORM\HasLifecycleCallbacks()]
#[ORM\Table(name: '`subscription`')]
class Subscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'uuid')]
    private string $payment_reference;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $checkout_session_id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $operator_transaction_id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private int $amount;

    #[ORM\Column(type: 'string', length: 5, nullable: true)]
    private string $currency;

    #[ORM\Column(type: 'string', length: 5, nullable: true)]
    private string $operator;

    #[ORM\Column(type: 'string', nullable: true)]
    private string $payment_mode;

    #[ORM\Column(type: 'string', nullable: true)]
    private string $payment_status;

    #[ORM\Column(type: 'string', nullable: true)]
    private string $payment_type;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $subscription_start_date;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $subscription_expire_date;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $payment_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $modified_at;

    #[ORM\ManyToOne(inversedBy: 'subscriptions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $subscriber = null;

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

    public function getSubscriber(): ?User
    {
        return $this->subscriber;
    }

    public function setSubscriber(?User $subscriber): self
    {
        $this->subscriber = $subscriber;

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
     * @return Subscription
     */
    public function setPaymentReference(?string $payment_reference): Subscription
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
     * @return Subscription
     */
    public function setCheckoutSessionId(?string $checkout_session_id): Subscription
    {
        $this->checkout_session_id = $checkout_session_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return Subscription
     */
    public function setAmount(?int $amount): Subscription
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
     * @return Subscription
     */
    public function setCurrency(?string $currency): Subscription
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
     * @return Subscription
     */
    public function setPaymentMode(?string $payment_mode): Subscription
    {
        $this->payment_mode = $payment_mode;
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
     * @return Subscription
     */
    public function setPaymentStatus(?string $payment_status): Subscription
    {
        $this->payment_status = $payment_status;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentType(): ?string
    {
        return $this->payment_type;
    }

    /**
     * @param string $payment_type
     * @return Subscription
     */
    public function setPaymentType(?string $payment_type): Subscription
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
     * @return Subscription
     */
    public function setPaymentDate(?\DateTimeInterface $payment_date): Subscription
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
     * @return Subscription
     */
    public function setCreatedAt(?\DateTimeInterface $created_at): Subscription
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
     * @return Subscription
     */
    public function setModifiedAt(?\DateTimeInterface $modified_at): Subscription
    {
        $this->modified_at = $modified_at;
        return $this;
    }

    /**
     * @return string
     */
    public function getOperator(): ?string
    {
        return $this->operator;
    }

    /**
     * @param string $operator
     * @return Subscription
     */
    public function setOperator(?string $operator): Subscription
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * @return string
     */
    public function getOperatorTransactionId(): ?string
    {
        return $this->operator_transaction_id;
    }

    /**
     * @param string $operator_transaction_id
     * @return Subscription
     */
    public function setOperatorTransactionId(?string $operator_transaction_id): Subscription
    {
        $this->operator_transaction_id = $operator_transaction_id;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getSubscriptionStartDate() : ?\DateTime
    {
        return $this->subscription_start_date;
    }

    /**
     * @param \DateTime $subscription_start_date
     * @return \DateTime
     */
    public function setSubscriptionStartDate(?\DateTime $subscription_start_date): self
    {
        $this->subscription_start_date = $subscription_start_date;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getSubscriptionExpireDate() : ?\DateTime
    {
        return $this->subscription_expire_date;
    }

    /**
     * @param \DateTime $subscription_expire_date
     * @return Subscription
     */
    public function setSubscriptionExpireDate(?\DateTime $subscription_expire_date)
    {
        $this->subscription_expire_date = $subscription_expire_date;
        return $this;
    }

}
