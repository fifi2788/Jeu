<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PlatRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: PlatRepository::class)]
#[ORM\Table(name: 'plat')]
class Plat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_plat', type: 'integer')]
    #[Groups(['plat:read'])]
    private ?int $id = null;

    #[ORM\Column(name: 'nom_plat', type: 'string')]
    #[Groups(['plat:read'])]
    private string $nomPlat;

    #[ORM\Column(name: 'url', type: 'string')]
    #[Groups(['plat:read'])]
    private ?string $url = null;

    #[ORM\Column(name: 'logo', type: 'string')]
    #[Groups(['plat:read'])]
    private ?string $logo = null;

    #[ORM\Column(name: 'temps_cuisson', type: 'integer')]
    #[Groups(['plat:read'])]
    private ?int $tempsCuisson = 0;

    #[ORM\Column(name: 'prix', type: 'float')]
    #[Groups(['plat:read'])]
    private ?float $prix = 0;

    // Helper method to calculate the estimated finish time
    /*public function getEstimatedFinishTime(): \DateTimeInterface
    {
        $estimatedFinishTime = clone $this->dateCreation;
        $estimatedFinishTime->modify("+{$this->tempsCuisson} seconds");
        return $estimatedFinishTime;
    }*/


    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPlat(): string
    {
        return $this->nomPlat;
    }

    public function setNomPlat(string $nomPlat): self
    {
        $this->nomPlat = $nomPlat;
        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function getTempsCuisson(): ?int
    {
        return $this->tempsCuisson;
    }

    public function setTempsCuisson(?int $tempsCuisson): self
    {
        $this->tempsCuisson = $tempsCuisson;
        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;
        return $this;
    }

}
