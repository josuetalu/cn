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

    /**
     * @ORM\OneToOne(targetEntity=OutOfService::class, mappedBy="delivery", cascade={"persist", "remove"})
     */
    private $isOutOfService;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $recipient;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $supportingDoc;

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

    public function getIsOutOfService(): ?OutOfService
    {
        return $this->isOutOfService;
    }

    public function setIsOutOfService(OutOfService $isOutOfService): self
    {
        // set the owning side of the relation if necessary
        if ($isOutOfService->getDelivery() !== $this) {
            $isOutOfService->setDelivery($this);
        }

        $this->isOutOfService = $isOutOfService;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getRecipient(): ?string
    {
        return $this->recipient;
    }

    public function setRecipient(string $recipient): self
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function getSupportingDoc(): ?string
    {
        return $this->supportingDoc;
    }

    public function setSupportingDoc(string $supportingDoc): self
    {
        $this->supportingDoc = $supportingDoc;

        return $this;
    }
}
