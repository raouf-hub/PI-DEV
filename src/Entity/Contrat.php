<?php

namespace App\Entity;

use App\Repository\ContratRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContratRepository::class)]
class Contrat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_deb = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_fin = null;

    #[ORM\Column(length: 255)]
    private ?string $type_de_contrat = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Voiture $matricule = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\ManyToOne]
    private ?User $client = null;

    #[ORM\Column(length: 255)]
    private ?string $paymentLink = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDeb(): ?\DateTime
    {
        return $this->date_deb;
    }

    public function setDateDeb(\DateTime $date_deb): self
    {
        $this->date_deb = $date_deb;

        return $this;
    }

    public function getDateFin(): ?\DateTime
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTime $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getTypeDeContrat(): ?string
    {
        return $this->type_de_contrat;
    }

    public function setTypeDeContrat(string $type_de_contrat): self
    {
        $this->type_de_contrat = $type_de_contrat;

        return $this;
    }

    public function getMatricule(): ?Voiture
    {
        return $this->matricule;
    }

    public function setMatricule(?Voiture $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

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

    public function getPaymentLink(): ?string
    {
        return $this->paymentLink;
    }

    public function setPaymentLink(string $paymentLink): self
    {
        $this->paymentLink = $paymentLink;

        return $this;
    }
}
