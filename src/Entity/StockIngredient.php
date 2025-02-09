<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\StockIngredientRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: StockIngredientRepository::class)]
#[ORM\Table(name: 'stock_ingredient')]
class StockIngredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_stock_ingredient', type: 'integer')]
    #[Groups(['stock_ingredient:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Ingredient::class)]
    #[ORM\JoinColumn(name: 'id_ingredient', referencedColumnName: 'id_ingredient', nullable: false)]
    #[MaxDepth(1)] //#[Ignore]
    private Ingredient $ingredient;

    #[ORM\Column(name: 'entree', type: 'float')]
    #[Groups(['stock_ingredient:read'])]
    private ?float $valeurEntree = 0;

    #[ORM\Column(name: 'sortie', type: 'float')]
    #[Groups(['stock_ingredient:read'])]
    private ?float $valeurSortie = 0;

    #[ORM\Column(name: 'date_mouvement', type: 'datetime')]
    #[Groups(['stock_ingredient:read'])]
    private ?\Datetime $dateMouvement;

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValeurEntree(): ?float
    {
        return $this->valeurEntree;
    }

    public function setValeurEntree(?float $valeurEntree): self
    {
        $this->valeurEntree = $valeurEntree;
        return $this;
    }

    public function getValeurSortie(): ?float
    {
        return $this->valeurSortie;
    }

    public function setValeurSortie(?float $valeurSortie): self
    {
        $this->valeurSortie = $valeurSortie;
        return $this;
    }

    public function getDateMouvement(): ?\Datetime
    {
        return $this->dateMouvement;
    }

    public function setDateMouvement(?\Datetime $dateMouvement): self
    {
        $this->dateMouvement = $dateMouvement;
        return $this;
    }

    public function getIngredient(): ?Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(Ingredient $ingredient): self
    {
        $this->ingredient = $ingredient;
        return $this;
    }

}
