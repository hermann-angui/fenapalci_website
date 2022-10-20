<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Date;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\HasLifecycleCallbacks()]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, unique: true, nullable: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    private $plain_password;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $lastname;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $place_of_birth;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $date_of_birth;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $nationality;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $sex;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $cni;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $passport;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $phone_number;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $address;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $city;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $commune;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $quartier;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $photo;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $type;

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

    #[ORM\OneToMany(mappedBy: 'payer', targetEntity: PaymentTransaction::class, orphanRemoval: true)]
    private Collection $payments;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Company::class, orphanRemoval: true)]
    private Collection $companies;

    #[ORM\OneToMany(mappedBy: 'payer', targetEntity: PaymentTransaction::class, orphanRemoval: true)]
    private Collection $paymentTransactions;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
        $this->companies = new ArrayCollection();
        $this->paymentTransactions = new ArrayCollection();
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
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

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
       $this->plain_password = null;
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

    /**
     * @return Collection<int, PaymentTransaction>
     */
    public function getPaymentTransactions(): Collection
    {
        return $this->payments;
    }

    public function addPaymentTransaction(PaymentTransaction $PaymentTransaction): self
    {
        if (!$this->payments->contains($PaymentTransaction)) {
            $this->payments[] = $PaymentTransaction;
            $PaymentTransaction->setPayer($this);
        }

        return $this;
    }

    public function removePaymentTransaction(PaymentTransaction $PaymentTransaction): self
    {
        if ($this->payments->removeElement($PaymentTransaction)) {
            // set the owning side to null (unless already changed)
            if ($PaymentTransaction->getPayer() === $this) {
                $PaymentTransaction->setPayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Company>
     */
    public function getCompanies(): Collection
    {
        return $this->companies;
    }

    public function addCompany(Company $company): self
    {
        if (!$this->companies->contains($company)) {
            $this->companies[] = $company;
            $company->setOwner($this);
        }

        return $this;
    }

    public function removeCompany(Company $company): self
    {
        if ($this->companies->removeElement($company)) {
            // set the owning side to null (unless already changed)
            if ($company->getOwner() === $this) {
                $company->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCni()
    {
        return $this->cni;
    }

    /**
     * @param mixed $cni
     * @return User
     */
    public function setCni($cni)
    {
        $this->cni = $cni;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassport()
    {
        return $this->passport;
    }

    /**
     * @param mixed $passport
     * @return User
     */
    public function setPassport($passport)
    {
        $this->passport = $passport;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCommune()
    {
        return $this->commune;
    }

    /**
     * @param mixed $commune
     * @return User
     */
    public function setCommune($commune)
    {
        $this->commune = $commune;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return User
     */
    public function setType($type)
    {
        $this->type = $type;
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
     * @param mixed $status
     * @return User
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getPayments(): ArrayCollection|Collection
    {
        return $this->payments;
    }

    /**
     * @param ArrayCollection|Collection $payments
     * @return User
     */
    public function setPayments(ArrayCollection|Collection $payments): User
    {
        $this->payments = $payments;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuartier()
    {
        return $this->quartier;
    }

    /**
     * @param mixed $quartier
     * @return User
     */
    public function setQuartier($quartier)
    {
        $this->quartier = $quartier;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubscriptionStartDate()
    {
        return $this->subscription_start_date;
    }

    /**
     * @param mixed $subscription_start_date
     * @return User
     */
    public function setSubscriptionStartDate($subscription_start_date)
    {
        $this->subscription_start_date = $subscription_start_date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubscriptionExpireDate()
    {
        return $this->subscription_expire_date;
    }

    /**
     * @param mixed $subscription_expire_date
     * @return User
     */
    public function setSubscriptionExpireDate($subscription_expire_date)
    {
        $this->subscription_expire_date = $subscription_expire_date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plain_password;
    }

    /**
     * @param mixed $plain_password
     * @return User
     */
    public function setPlainPassword($plain_password)
    {
        $this->plain_password = $plain_password;
        return $this;
    }

}
