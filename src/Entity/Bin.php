<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BinRepository")
 */
class Bin
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /** 
     * @ORM\Column(type="date", nullable=true)
     */
    private ?\DateTimeInterface $grantDate = null;

    /** 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $serial = null;

    /** 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $range1 = null;

    /** 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $range2 = null;

    /** 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $lastEmittedPan = null;

    /**
     * @ORM\ManyToOne(targetEntity=Bank::class, inversedBy="bins")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bank;


    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="bin")
     */
    private Collection $orders;

    
    public function __construct()
    {
        $this->orders = new ArrayCollection();
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

    public function getGrantDate(): ?\DateTimeInterface
    {
        return $this->grantDate;
    }

    public function setGrantDate(?\DateTimeInterface $grantDate): self
    {
        $this->grantDate = $grantDate;

        return $this;
    }

    public function getSerial(): ?string
    {
        return $this->serial;
    }

    public function setSerial(?string $serial): self
    {
        $this->serial = $serial;

        return $this;
    }

    public function getRange1(): ?string
    {
        return $this->range1;
    }

    public function setRange1(?string $range1): self
    {
        $this->range1 = $range1;

        return $this;
    }

    public function getRange2(): ?string
    {
        return $this->range2;
    }

    public function setRange2(?string $range2): self
    {
        $this->range2 = $range2;

        return $this;
    }

    public function getLastEmittedPan(): ?string
    {
        return $this->lastEmittedPan;
    }

    public function setLastEmittedPan(?string $lastEmittedPan): self
    {
        $this->lastEmittedPan = $lastEmittedPan;

        return $this;
    }

    public function getBank(): ?Bank
    {
        return $this->bank;
    }

    public function setBank(?Bank $bank): self
    {
        $this->bank = $bank;

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }
    
    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setBin($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // Set the owning side to null (unless already changed)
            if ($order->getBin() === $this) {
                $order->setBin(null);
            }
        }

        return $this;
    }


}
