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

    #[ORM\Column(type: 'string', length: 255)]
    private string $payment_reference;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $amount;

    #[ORM\Column(type: 'string', length: 5, nullable: true)]
    private string $currency;

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

}
