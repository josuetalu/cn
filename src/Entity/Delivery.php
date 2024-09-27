<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeliveryRepository")
 */
class Delivery
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
    private ?string $deliveryCode = null;

    /** 
     * @ORM\Column(type="date", nullable=true)
     */
    private ?\DateTimeInterface $date = null;

    /** 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $range1 = null;

    /** 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $range2 = null;

    /** 
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $cardTotal = null;

    // Getters and Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeliveryCode(): ?string
    {
        return $this->deliveryCode;
    }

    public function setDeliveryCode(?string $deliveryCode): self
    {
        $this->deliveryCode = $deliveryCode;
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

    public function getCardTotal(): ?int
    {
        return $this->cardTotal;
    }

    public function setCardTotal(?int $cardTotal): self
    {
        $this->cardTotal = $cardTotal;
        return $this;
    }
}
