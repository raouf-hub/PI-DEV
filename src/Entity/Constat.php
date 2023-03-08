<?php

namespace App\Entity;

use App\Repository\ConstatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ConstatRepository::class)]
#@Vich\Uploadable
class Constat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("post:read")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'le chemin n est pas disponible')]
    #[Groups("post:read")]
    private ?string $TypeDAccident = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'le chemin n est pas disponible')]
    #[Groups("post:read")]
    private ?string $LieuDAccident = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'le chemin n est pas disponible')]
    #[Groups("post:read")]
    private ?string $vehiculeA = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'le chemin n est pas disponible')]
    #[Groups("post:read")]
    private ?string $VehiculeB = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'le chemin n est pas disponible')]
    #[Groups("post:read")]
    private ?string $immatriculeA = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'le chemin n est pas disponible')]
    #[Groups("post:read")]
    private ?string $immatriculeB = null;
    #[Groups("post:read")]
    #[Assert\NotBlank( message:'le chemin n est pas disponible')]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    
   
    private ?\DateTimeInterface $DateDAccident = null;

    #[ORM\ManyToOne(inversedBy: 'constats')]
    private ?Sinstre $sinstre = null;


    public function __construct()
    {
        $this->sinstres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeDAccident(): ?string
    {
        return $this->TypeDAccident;
    }

    public function setTypeDAccident(string $TypeDAccident): self
    {
        $this->TypeDAccident = $TypeDAccident;

        return $this;
    }

    public function getLieuDAccident(): ?string
    {
        return $this->LieuDAccident;
    }

    public function setLieuDAccident(string $LieuDAccident): self
    {
        $this->LieuDAccident = $LieuDAccident;

        return $this;
    }

    public function getVehiculeA(): ?string
    {
        return $this->vehiculeA;
    }

    public function setVehiculeA(string $vehiculeA): self
    {
        $this->vehiculeA = $vehiculeA;

        return $this;
    }

    public function getVehiculeB(): ?string
    {
        return $this->VehiculeB;
    }

    public function setVehiculeB(string $VehiculeB): self
    {
        $this->VehiculeB = $VehiculeB;

        return $this;
    }

    public function getImmatriculeA(): ?string
    {
        return $this->immatriculeA;
    }

    public function setImmatriculeA(string $immatriculeA): self
    {
        $this->immatriculeA = $immatriculeA;

        return $this;
    }

    public function getImmatriculeB(): ?string
    {
        return $this->immatriculeB;
    }

    public function setImmatriculeB(string $immatriculeB): self
    {
        $this->immatriculeB = $immatriculeB;

        return $this;
    }

    public function getDateDAccident(): ?\DateTimeInterface
    {
        return $this->DateDAccident;
    }

    public function setDateDAccident(\DateTimeInterface $DateDAccident): self
    {
        $this->DateDAccident = $DateDAccident;

        return $this;
    }

    public function getSinstre(): ?Sinstre
    {
        return $this->sinstre;
    }

    public function setSinstre(?Sinstre $sinstre): self
    {
        $this->sinstre = $sinstre;

        return $this;
    }

   
    
}
