<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\RecetteRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: RecetteRepository::class)]
#[ORM\Table(name: 'recette')]
class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_recette', type: 'integer')]
    #[Groups(['recette:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Plat::class)]
    #[ORM\JoinColumn(name: 'id_plat', referencedColumnName: 'id_plat', nullable: false)]
    #[MaxDepth(1)] //#[Ignore]
    private Plat $plat;

    #[ORM\ManyToOne(targetEntity: Ingredient::class)]
    #[ORM\JoinColumn(name: 'id_ingredient', referencedColumnName: 'id_ingredient', nullable: false)]
    #[MaxDepth(1)] //#[Ignore]
    private Ingredient $ingredient;

    #[ORM\Column(name: 'quantite_ingredient', type: 'float')]
    #[Groups(['recette:read'])]
    private ?float $quantiteIngredient = 0.00;


    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlat(): ?Plat
    {
        return $this->plat;
    }

    public function setPlat(?Plat $plat): self
    {
        $this->plat = $plat;
        return $this;
    }

    public function getIngredient(): ?Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(?Ingredient $ingredient): self
    {
        $this->ingredient = $ingredient;
        return $this;
    }

    public function getQuantiteIngredient(): ?float
    {
        return $this->quantiteIngredient;
    }

    public function setQuantiteIngredient(?float $quantiteIngredient): self
    {
        $this->quantiteIngredient = $quantiteingredient;
        return $this;
    }

}
