<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BankRepository")
 */
class Bank
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /** 
     * @ORM\Column(type="string", length=255)
     */
    private string $bankCode;

    /** 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $address = null;

    /** 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $contact = null;

    /** 
     * @ORM\Column(type="date", nullable=true)
     */
    private ?\DateTimeInterface $date = null;

    /** 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $defaultEmail = null;

    /** 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $website = null;

    /** 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $defaultNumber = null;

    /**
     * @ORM\OneToMany(targetEntity=Bin::class, mappedBy="bank")
     */
    private $bins;

    public function __construct()
    {
        $this->bins = new ArrayCollection();
    }

    // Implement the __toString method
    public function __toString(): string
    {
        return $this->bankCode ?? ''; // Ensure this returns a non-null string
    }

    // Getters and setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBankCode(): string
    {
        return $this->bankCode;
    }

    public function setBankCode(string $bankCode): self
    {
        $this->bankCode = $bankCode;

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

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDefaultEmail(): ?string
    {
        return $this->defaultEmail;
    }

    public function setDefaultEmail(?string $defaultEmail): self
    {
        $this->defaultEmail = $defaultEmail;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getDefaultNumber(): ?string
    {
        return $this->defaultNumber;
    }

    public function setDefaultNumber(?string $defaultNumber): self
    {
        $this->defaultNumber = $defaultNumber;

        return $this;
    }

    /**
     * @return Collection<int, Bin>
     */
    public function getBins(): Collection
    {
        return $this->bins;
    }

    public function addBin(Bin $bin): self
    {
        if (!$this->bins->contains($bin)) {
            $this->bins[] = $bin;
            $bin->setBank($this);
        }

        return $this;
    }

    public function removeBin(Bin $bin): self
    {
        if ($this->bins->removeElement($bin)) {
            // set the owning side to null (unless already changed)
            if ($bin->getBank() === $this) {
                $bin->setBank(null);
            }
        }

        return $this;
    }
}
