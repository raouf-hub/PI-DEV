<?php

namespace App\Entity;

use App\Repository\SinstreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Annotation\Groups;



#[ORM\Entity(repositoryClass: SinstreRepository::class)]
#@Vich\Uploadable
class Sinstre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("post:read")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'le chemin n est pas disponible')]
    #[Groups("post:read")]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'le chemin n est pas disponible')]
    #[Groups("sinstres")]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'Email  n est pas valide ')]
    
    #[Groups("sinstres")]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'le chemin n est pas disponible')]
    #[Groups("sinstres")]
    private ?string $date = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'le chemin n est pas disponible')]
    #[Groups("sinstres")]
    private ?string $Adresse = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'le chemin n est pas disponible')]
    #[Groups("sinstres")]
    private ?string $typeduvehicule = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'le chemin n est pas disponible')]
    #[Groups("sinstres")]
    private ?string $fichieraccident = null;

    #[ORM\OneToMany(mappedBy: 'sinstre', targetEntity: Constat::class)]
    private Collection $constats;

    #[ORM\ManyToOne]
    private ?User $client = null;
  

    public function __construct()
    {
        $this->constats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(string $Adresse): self
    {
        $this->Adresse = $Adresse;

        return $this;
    }

    public function getTypeduvehicule(): ?string
    {
        return $this->typeduvehicule;
    }

    public function setTypeduvehicule(string $typeduvehicule): self
    {
        $this->typeduvehicule = $typeduvehicule;

        return $this;
    }

    public function getFichieraccident(): ?string
    {
        return $this->fichieraccident;
    }

    public function setFichieraccident(string $fichieraccident): self
    {
        $this->fichieraccident = $fichieraccident;

        return $this;
    }

    /**
     * @return Collection<int, constat>
     */
    public function getConstats(): Collection
    {
        return $this->constats;
    }

    public function addConstat(Constat $constat): self
    {
        if (!$this->constats->contains($constat)) {
            $this->constats->add($constat);
            $constat->setSinstre($this);
        }

        return $this;
    }

    public function removeConstat(Constat $constat): self
    {
        if ($this->constats->removeElement($constat)) {
            // set the owning side to null (unless already changed)
            if ($constat->getSinstre() === $this) {
                $constat->setSinstre(null);
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
