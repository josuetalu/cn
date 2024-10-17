<?php

namespace App\Entity;

use App\Repository\OutOfServiceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OutOfServiceRepository::class)
 */
class OutOfService
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $delivery_code;

    /**
     * @ORM\Column(type="date")
     */
    private $delivery_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $range1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $range2;

    /**
     * @ORM\Column(type="integer")
     */
    private $total_card;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reason;

    /**
     * @ORM\Column(type="date")
     */
    private $lock_date;

    /**
     * @ORM\OneToOne(targetEntity=Delivery::class, inversedBy="isOutOfService", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $delivery;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeliveryCode(): ?string
    {
        return $this->delivery_code;
    }

    public function setDeliveryCode(string $delivery_code): self
    {
        $this->delivery_code = $delivery_code;

        return $this;
    }

    public function getDeliveryDate(): ?\DateTimeInterface
    {
        return $this->delivery_date;
    }

    public function setDeliveryDate(\DateTimeInterface $delivery_date): self
    {
        $this->delivery_date = $delivery_date;

        return $this;
    }

    public function getRange1(): ?string
    {
        return $this->range1;
    }

    public function setRange1(string $range1): self
    {
        $this->range1 = $range1;

        return $this;
    }

    public function getRange2(): ?string
    {
        return $this->range2;
    }

    public function setRange2(string $range2): self
    {
        $this->range2 = $range2;

        return $this;
    }

    public function getTotalCard(): ?int
    {
        return $this->total_card;
    }

    public function setTotalCard(int $total_card): self
    {
        $this->total_card = $total_card;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getLockDate(): ?\DateTimeInterface
    {
        return $this->lock_date;
    }

    public function setLockDate(\DateTimeInterface $lock_date): self
    {
        $this->lock_date = $lock_date;

        return $this;
    }

    public function getDelivery(): ?Delivery
    {
        return $this->delivery;
    }

    public function setDelivery(Delivery $delivery): self
    {
        $this->delivery = $delivery;

        return $this;
    }
}
