<?php

namespace App\Entity;

use App\Repository\RangeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RangeRepository::class)
 * @ORM\Table(name="`range`")
 */
class Range
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
    private $start;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $end;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Bin::class, inversedBy="ranges")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bin;

    /**
     * @ORM\OneToMany(targetEntity=OrderRange::class, mappedBy="_range")
     */
    private $orderRanges;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $last_emitted_pan;

    public function __construct()
    {
        $this->orderRanges = new ArrayCollection();
    }



    public function __toString(): string
    {
        return "mm"; // Remplacez par la propriété que vous voulez afficher
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): ?string
    {
        return $this->start;
    }

    public function setStart(string $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?string
    {
        return $this->end;
    }

    public function setEnd(string $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

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


    public function getAvailableSpace()
    {  
        if($this->getLastEmittedPan() == null)
        {
            $start = (int) $this->getStart();
        }else{
            $start = ((int) substr($this->getLastEmittedPan(), 6));      
        }
       $end = (int) $this->getEnd();
       return $available = ( ( $end - $start));
    }

    /*public getLastEmittedPanForTheRangeBefore()
    {

    }*/

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
            $orderRange->setRange($this);
        }

        return $this;
    }

    public function removeOrderRange(OrderRange $orderRange): self
    {
        if ($this->orderRanges->removeElement($orderRange)) {
            // set the owning side to null (unless already changed)
            if ($orderRange->getRange() === $this) {
                $orderRange->setRange(null);
            }
        }

        return $this;
    }

    public function getLastEmittedPan(): ?string
    {
        return $this->last_emitted_pan;
    }

    public function setLastEmittedPan(?string $last_emitted_pan): self
    {
        $this->last_emitted_pan = $last_emitted_pan;

        return $this;
    }
}
