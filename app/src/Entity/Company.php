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
    #[Groups(['company'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['company'])]
    private $name;
    
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['company'])]
    private $phone_number;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['company'])]
    private $address;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['company'])]
    private $status;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['company'])]
    private $date_created = null;

    #[ORM\ManyToOne(inversedBy: 'companies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Staff::class, orphanRemoval: true)]
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
     * @return Collection<int, Staff>
     */
    public function getStaff(): Collection
    {
        return $this->staff;
    }

    public function addStaff(Staff $staff): self
    {
        if (!$this->staff->contains($staff)) {
            $this->staff[] = $staff;
            $staff->setCompany($this);
        }

        return $this;
    }

    public function removeStaff(Staff $staff): self
    {
        if ($this->staff->removeElement($staff)) {
            // set the owning side to null (unless already changed)
            if ($staff->getCompany() === $this) {
                $staff->setCompany(null);
            }
        }

        return $this;
    }
}
