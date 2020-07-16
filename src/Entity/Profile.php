<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Entity\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ProfileRepository::class)
 */
class Profile
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
     * @ORM\Column(type="string", length=255)
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $quotation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $profile_img;

    /**
     * @ORM\OneToMany(targetEntity=Component::class, mappedBy="profile")
     */
    private $components;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cvlink;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cv;


    public function __construct()
    {
        $this->components = new ArrayCollection();
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

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getQuotation(): ?string
    {
        return $this->quotation;
    }

    public function setQuotation(?string $quotation): self
    {
        $this->quotation = $quotation;

        return $this;
    }

    public function getProfileImg(): ?string
    {
        return $this->profile_img;
    }

    public function setProfileImg(string $profile_img): self
    {
        $this->profile_img = $profile_img;

        return $this;
    }

    /**
     * @return Collection|Component[]
     */
    public function getComponents(): Collection
    {
        return $this->components;
    }

    public function addComponent(Component $component): self
    {
        if (!$this->components->contains($component)) {
            $this->components[] = $component;
            $component->setProfile($this);
        }

        return $this;
    }

    public function removeComponent(Component $component): self
    {
        if ($this->components->contains($component)) {
            $this->components->removeElement($component);
            // set the owning side to null (unless already changed)
            if ($component->getProfile() === $this) {
                $component->setProfile(null);
            }
        }

        return $this;
    }

    public function getCvlink(): ?string
    {
        return $this->cvlink;
    }

    public function setCvlink(?string $cvlink): self
    {
        $this->cvlink = $cvlink;

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(?string $cv): self
    {
        $this->cv = $cv;

        return $this;
    }
}
