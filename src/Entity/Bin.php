<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Assert\Length(
     *      min = 10,
     *      max = 10,
     *      exactMessage = "Un BIN doit avoir exactement {{ limit }} caractères."
     * )
     */
    private ?string $range1 = null;

    /** 
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Assert\Length(
     *      min = 10,
     *      max = 10,
     *      exactMessage = "Un BIN doit avoir exactement {{ limit }} caractères."
     * )
     */
    private ?string $range2 = null;

    /** 
     * @ORM\Column(type="string", length=16, nullable=true)
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

    /**
     * @ORM\OneToMany(targetEntity=Range::class, mappedBy="bin")
     */
    private $ranges;

    
    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->ranges = new ArrayCollection();
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

    /**
     * @return Collection<int, Range>
     */
    public function getRanges(): Collection
    {
        return $this->ranges;
    }

    public function addRange(Range $range): self
    {
        if (!$this->ranges->contains($range)) {
            $this->ranges[] = $range;
            $range->setBin($this);
        }

        return $this;
    }

    public function removeRange(Range $range): self
    {
        if ($this->ranges->removeElement($range)) {
            // set the owning side to null (unless already changed)
            if ($range->getBin() === $this) {
                $range->setBin(null);
            }
        }

        return $this;
    }






}
