<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[ORM\HasLifecycleCallbacks()]
#[ORM\Table(name: '`company`')]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;
    
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $phone_number;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $address;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $ville;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $commune;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $quartier;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $category;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $legal_status;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $registre_commerce;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $status;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $subscription_start_date;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $subscription_expire_date;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $date_created = null;

    #[ORM\ManyToOne(inversedBy: 'companies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Employee::class, orphanRemoval: true)]
    private Collection $staff;

    public function __construct()
    {
        $this->staff = new ArrayCollection();
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, Employee>
     */
    public function getStaff(): Collection
    {
        return $this->staff;
    }

    public function addStaff(Employee $staff): self
    {
        if (!$this->staff->contains($staff)) {
            $this->staff[] = $staff;
            $staff->setCompany($this);
        }

        return $this;
    }

    public function removeStaff(Employee $staff): self
    {
        if ($this->staff->removeElement($staff)) {
            // set the owning side to null (unless already changed)
            if ($staff->getCompany() === $this) {
                $staff->setCompany(null);
            }
        }

        return $this;
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
     * @return Company
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Company
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }

    /**
     * @param mixed $phone_number
     * @return Company
     */
    public function setPhoneNumber($phone_number)
    {
        $this->phone_number = $phone_number;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     * @return Company
     */
    public function setAddress($address)
    {
        $this->address = $address;
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
     * @return Company
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return null
     */
    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
     * @param null $date_created
     * @return Company
     */
    public function setDateCreated($date_created)
    {
        $this->date_created = $date_created;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * @param mixed $ville
     * @return Company
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
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
     * @return Company
     */
    public function setCommune($commune)
    {
        $this->commune = $commune;
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
     * @return Company
     */
    public function setQuartier($quartier)
    {
        $this->quartier = $quartier;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     * @return Company
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLegalStatus()
    {
        return $this->legal_status;
    }

    /**
     * @param mixed $legal_status
     * @return Company
     */
    public function setLegalStatus($legal_status)
    {
        $this->legal_status = $legal_status;
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
     * @return Company
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
     * @return Company
     */
    public function setSubscriptionExpireDate($subscription_expire_date)
    {
        $this->subscription_expire_date = $subscription_expire_date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRegistreCommerce()
    {
        return $this->registre_commerce;
    }

    /**
     * @param mixed $registre_commerce
     * @return Company
     */
    public function setRegistreCommerce($registre_commerce)
    {
        $this->registre_commerce = $registre_commerce;
        return $this;
    }


    public static function getSubscriptionFee (string $categorieName) : ?string
    {
        $categorieName = strtoupper($categorieName);
        $fees =     [
            "GLACIER" => 5000,
            "BAR"  => 5000,
            "MAQUIS : 1-50 Places" => 2000,
            "MAQUIS : 50-100 Places"=> 3000,
            "MAQUIS : 101 Places et Plus" => 5000,
            "RESTAURANT : 1-50 Places" => 2000,
            "RESTAURANT : 51 Places et Plus"=> 3000,
            "MAQUIS/RESTAURANT"  => 5000,
            "RESTAURANT VIP" => 5000,
            "DEPOT DE BOISSON"  => 5000,
            "HOTEL"   => 5000,
            "NIGHT CLUB" => 5000,
            "EVENEMENTIEL" => 5000,
            "PATISSERIE" => 5000,
            "CAVE : 1-50 Places"  => 2000,
            "CAVE : 51 Places et Plus" => 3000
        ];

        if(key_exists($categorieName, $fees)) return $fees[$categorieName];
        else return 0;

    }

    public static function getCategories () : array
    {
        return  [
            "GLACIER" => "GLACIER" ,
            "BAR" => "BAR",
            "MAQUIS : 1-50 Places" => "MAQUIS : 1-50 Places",
            "MAQUIS : 50-100 Places" => "MAQUIS : 50-100 Places",
            "MAQUIS : 101 Places et Plus" => "MAQUIS : 101 Places et Plus",
            "RESTAURANT : 1-50 Places" => "RESTAURANT : 1-50 Places",
            "RESTAURANT : 51 Places et Plus" => "RESTAURANT : 51 Places et Plus",
            "MAQUIS/RESTAURANT" => "MAQUIS/RESTAURANT",
            "RESTAURANT VIP" => "RESTAURANT VIP",
            "DEPOT DE BOISSON"  => "DEPOT DE BOISSON",
            "HOTEL"   => "HOTEL",
            "NIGHT CLUB" => "NIGHT CLUB",
            "EVENEMENTIEL" => "EVENEMENTIEL",
            "PATISSERIE" => "PATISSERIE",
            "CAVE : 1-50 Places"  => "CAVE : 1-50 Places",
            "CAVE : 51 Places et Plus" => "CAVE : 51 Places et Plus"
        ];

    }
}
