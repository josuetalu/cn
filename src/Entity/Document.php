<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 */
class Document
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
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $postName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $gender;

    /**
     * @ORM\ManyToOne(targetEntity=Step::class, inversedBy="documents")
     */
    private $step;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBack;

    /**
     * @ORM\OneToMany(targetEntity=Action::class, mappedBy="document")
     */
    private $actions;

    public function __construct()
    {
        $this->actions = new ArrayCollection();
    }

    
    public function __toString(): string
    {
        return $this->firstName; // Ou tout autre champ pertinent
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPostName(): ?string
    {
        return $this->postName;
    }

    public function setPostName(string $postName): self
    {
        $this->postName = $postName;

        return $this;
    }

    public function isGender(): ?bool
    {
        return $this->gender;
    }

    public function setGender(bool $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getStep(): ?Step
    {
        return $this->step;
    }

    public function setStep(?Step $step): self
    {
        $this->step = $step;

        return $this;
    }

    public function isIsBack(): ?bool
    {
        return $this->isBack;
    }

    public function setIsBack(bool $isBack): self
    {
        $this->isBack = $isBack;

        return $this;
    }

    /**
     * @return Collection<int, Action>
     */
    public function getActions(): Collection
    {
        // Convertir la collection en un tableau, inverser l'ordre, puis recréer une collection
        $reversedActions = array_reverse($this->actions->toArray());
    
        return new ArrayCollection($reversedActions);
    }

    public function addAction(Action $action): self
    {
        if (!$this->actions->contains($action)) {
            $this->actions[] = $action;
            $action->setDocument($this);
        }

        return $this;
    }

    public function removeAction(Action $action): self
    {
        if ($this->actions->removeElement($action)) {
            // set the owning side to null (unless already changed)
            if ($action->getDocument() === $this) {
                $action->setDocument(null);
            }
        }

        return $this;
    }
}
