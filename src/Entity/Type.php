<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeRepository::class)
 */
class Type
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Component::class, mappedBy="type", cascade="remove")
     */
    private $component;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $rules = [];

    public function __construct()
    {
        $this->component = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Component[]
     */
    public function getComponent(): Collection
    {
        return $this->component;
    }

    public function addComponent(Component $component): self
    {
        if (!$this->component->contains($component)) {
            $this->component[] = $component;
            $component->setType($this);
        }

        return $this;
    }

    public function removeComponent(Component $component): self
    {
        if ($this->component->contains($component)) {
            $this->component->removeElement($component);
            // set the owning side to null (unless already changed)
            if ($component->getType() === $this) {
                $component->setType(null);
            }
        }

        return $this;
    }

    public function getRules(): ?array
    {
        return $this->rules;
    }

    public function setRules(array $rules): self
    {
        $this->rules = $rules;

        return $this;
    }
}
