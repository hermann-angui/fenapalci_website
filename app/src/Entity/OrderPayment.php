<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: OrderPaymentRepository::class)]
#[ORM\Table(name: '`order`')]
#[ORM\HasLifecycleCallbacks()]
#[UniqueEntity(fields: ['id'], message: 'There is already an order with this id')]
class OrderPayment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $number;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $amount;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $tax;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $commission;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $installment_date;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $payment_date;

    #[ORM\Column(type: 'int', length: 255, nullable: true)]
    private $penality;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $created_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $modified_at;

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

    public function getId(): ?int
    {
        return $this->id;
    }
}
