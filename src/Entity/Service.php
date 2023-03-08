<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private $libelleService;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private $nomService;

    #[ORM\OneToMany(mappedBy: 'service', targetEntity: Remorquage::class)]
    #[Assert\NotBlank]
    private $remorquages;

    #[ORM\ManyToOne]
    private ?User $client = null;

    public function __construct()
    {
        $this->remorquages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleService(): ?string
    {
        return $this->libelleService;
    }

    public function setLibelleService(string $libelleService): self
    {
        $this->libelleService = $libelleService;

        return $this;
    }

    public function getNomService(): ?string
    {
        return $this->nomService;
    }

    public function setNomService(string $nomService): self
    {
        $this->nomService = $nomService;

        return $this;
    }
    
    public function __toString()
    {
        return (string) $this->libelleService;
    }

    /**
     * @return Collection<int, Remorquage>
     */
    public function getRemorquages(): Collection
    {
        return $this->remorquages;
    }

    public function addRemorquage(Remorquage $remorquage): self
    {
        if (!$this->remorquages->contains($remorquage)) {
            $this->remorquages[] = $remorquage;
            $remorquage->setService($this);
        }

        return $this;
    }

    public function removeRemorquage(Remorquage $remorquage): self
    {
        if ($this->remorquages->removeElement($remorquage)) {
            // set the owning side to null (unless already changed)
            if ($remorquage->getService() === $this) {
                $remorquage->setService(null);
            }
        }

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }
}
