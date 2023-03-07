<?php

namespace App\Entity;

use App\Repository\VoitureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoitureRepository::class)]
class Voiture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $matricule = null;

    #[ORM\Column(length: 50)]
    private ?string $couleur = null;

    #[ORM\Column]
    private ?int $Nombre_de_places = null;

    #[ORM\Column]
    private ?int $Puissance = null;

    #[ORM\Column(length: 50)]
    private ?string $Energie = null;

    #[ORM\ManyToOne(inversedBy: 'voitures')]
    private ?Marques $name = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getNombreDePlaces(): ?int
    {
        return $this->Nombre_de_places;
    }

    public function setNombreDePlaces(int $Nombre_de_places): self
    {
        $this->Nombre_de_places = $Nombre_de_places;

        return $this;
    }

    public function getPuissance(): ?int
    {
        return $this->Puissance;
    }

    public function setPuissance(int $Puissance): self
    {
        $this->Puissance = $Puissance;

        return $this;
    }

    public function getEnergie(): ?string
    {
        return $this->Energie;
    }

    public function setEnergie(string $Energie): self
    {
        $this->Energie = $Energie;

        return $this;
    }

    public function getName(): ?Marques
    {
        return $this->name;
    }

    public function setName(?Marques $name): self
    {
        $this->name = $name;

        return $this;
    }

  
}
