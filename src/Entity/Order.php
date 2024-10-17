<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /** 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $orderCode = null;

    /** 
     * @ORM\Column(type="datetime")
     */
    private $orderDate = null;


    /** 
     * @ORM\Column(type="datetime")
     */
    private $date = null;

    /** 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $serial = null;

    /** 
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $cardTotal = null;

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
    private ?string $cardType = null;


    /**
     * @ORM\ManyToOne(targetEntity=Bin::class, inversedBy="bins")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bin;

    /**
     * @ORM\Column(type="boolean")
     */
    private $delivered;

    /**
     * @ORM\OneToMany(targetEntity=OrderRange::class, mappedBy="_order")
     */
    private $orderRanges;

    public function __construct()
    {
        $this->orderRanges = new ArrayCollection();
    }

    /*// Implement the __toString method
    public function __toString(): string
    {
        return $this->serial ?? ''; // Ensure this returns a non-null string
    }*/

    // Getters and Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderCode(): ?string
    {
        return $this->orderCode;
    }

    public function setOrderCode(?string $orderCode): self
    {
        $this->orderCode = $orderCode;
        return $this;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(\DateTimeInterface $orderDate): self
    {
        $this->orderDate = $orderDate;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
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

    public function getCardTotal(): ?int
    {
        return $this->cardTotal;
    }

    public function setCardTotal(?int $cardTotal): self
    {
        $this->cardTotal = $cardTotal;
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

    public function getCardType(): ?string
    {
        return $this->cardType;
    }

    public function setCardType(?string $cardType): self
    {
        $this->cardType = $cardType;
        return $this;
    }

    public function getBin(): ?Bin
    {

        return $this->bin;
    }

    public function setBin(?Bin $bin): self
    {
        $this->bin = $bin;

        return $this;
    }

    public function isDelivered(): ?bool
    {
        return $this->delivered;
    }

    public function setDelivered(bool $delivered): self
    {
        $this->delivered = $delivered;

        return $this;
    }

    /**
     * @return Collection<int, OrderRange>
     */
    public function getOrderRanges(): Collection
    {
        return $this->orderRanges;
    }

    public function addOrderRange(OrderRange $orderRange): self
    {
        if (!$this->orderRanges->contains($orderRange)) {
            $this->orderRanges[] = $orderRange;
            $orderRange->setOrder($this);
        }

        return $this;
    }

    public function removeOrderRange(OrderRange $orderRange): self
    {
        if ($this->orderRanges->removeElement($orderRange)) {
            // set the owning side to null (unless already changed)
            if ($orderRange->getOrder() === $this) {
                $orderRange->setOrder(null);
            }
        }

        return $this;
    }
}
