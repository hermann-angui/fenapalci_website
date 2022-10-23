<?php

namespace App\Entity;

use App\Repository\StaffRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: StaffRepository::class)]
#[ORM\HasLifecycleCallbacks()]
#[ORM\Table(name: '`staff`')]
#[ORM\MappedSuperclass()]
class Staff
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $email;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $lastname;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $place_of_birth;

    #[ORM\Column(type: 'datetime', length: 255, nullable: true)]
    private $date_of_birth;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $nationality;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $cni;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $sex;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $phone_number;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $photo;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $status;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $subscription_start_date;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $subscription_expire_date;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $created_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $modified_at;

    #[ORM\ManyToOne(inversedBy: 'staff', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

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
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTime $createAt): self
    {
        $this->created_at = $createAt;

        return $this;
    }

    public function getModifiedAt(): ?\DateTime
    {
        return $this->modified_at;
    }

    public function setPlaceOfBirth(?string $placeofbirth): self
    {
        $this->place_of_birth = $placeofbirth;

        return $this;
    }

    public function getPlaceOfBirth(): ?string
    {
        return $this->place_of_birth;
    }

    public function getDateOfBirth(): ?\DateTime
    {
        return $this->date_of_birth;
    }

    public function setDateofBirth(?\DateTime $dateofbirth): self
    {
        $this->date_of_birth = $dateofbirth;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(?string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(?string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }


    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(?string $phonenumber): self
    {
        $this->phone_number = $phonenumber;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return Staff
     */
    public function setStatus(?string $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getSubscriptionStartDate(): ?\DateTime
    {
        return $this->subscription_start_date;
    }

    /**
     * @param \DateTime $subscription_start_date
     * @return Staff
     */
    public function setSubscriptionStartDate(?\DateTime $subscription_start_date): self
    {
        $this->subscription_start_date = $subscription_start_date;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getSubscriptionExpireDate(): ?\DateTime
    {
        return $this->subscription_expire_date;
    }

    /**
     * @param ?\DateTime $subscription_expire_date
     * @return Staff
     */
    public function setSubscriptionExpireDate(?\DateTime $subscription_expire_date): self
    {
        $this->subscription_expire_date = $subscription_expire_date;
        return $this;
    }

    /**
     * @return string
     */
    public function getCni() : ?string
    {
        return $this->cni;
    }

    /**
     * @param string $cni
     * @return Staff
     */
    public function setCni(?string $cni)
    {
        $this->cni = $cni;
        return $this;
    }


}
