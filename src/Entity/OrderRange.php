<?php

namespace App\Entity;

use App\Repository\OrderRangeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRangeRepository::class)
 */
class OrderRange
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="orderRanges")
     * @ORM\JoinColumn(nullable=false)
     */
    private $_order;

    /**
     * @ORM\ManyToOne(targetEntity=range::class, inversedBy="orderRanges")
     * @ORM\JoinColumn(nullable=false)
     */
    private $_range;

    /**
     * @ORM\Column(type="integer")
     */
    private $total;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $start_pan;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $end_pan;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): ?Order
    {
        return $this->_order;
    }

    public function setOrder(?Order $_order): self
    {
        $this->_order = $_order;

        return $this;
    }

    public function getRange(): ?range
    {
        return $this->_range;
    }

    public function setRange(?range $_range): self
    {
        $this->_range = $_range;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getStartPan(): ?string
    {
        return $this->start_pan;
    }

    public function setStartPan(string $start_pan): self
    {
        $this->start_pan = $start_pan;

        return $this;
    }

    public function getEndPan(): ?string
    {
        return $this->end_pan;
    }

    public function setEndPan(string $end_pan): self
    {
        $this->end_pan = $end_pan;

        return $this;
    }
}
