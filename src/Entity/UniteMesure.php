<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UniteMesureRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: UniteMesureRepository::class)]
#[ORM\Table(name: 'unite_mesure')]
class UniteMesure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_unite_mesure', type: 'integer')]
    #[Groups(['ingredient:read', 'uniteMesure:read'])]
    private ?int $id = null;

    #[ORM\Column(name: 'nom_unite', type: 'string')]
    #[Groups(['ingredient:read', 'uniteMesure:read'])]
    private string $nomUnite;

    #[ORM\OneToMany(mappedBy: 'uniteMesure', targetEntity: Ingredient::class)]
    private Collection $ingredients;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
    }

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomUnite(): string
    {
        return $this->nomUnite;
    }

    public function setNomUnite(string $nomUnite): self
    {
        $this->nomUnite = $nomUnite;
        return $this;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
            $ingredient->setUniteMesure($this);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        if ($this->ingredients->removeElement($ingredient)) {
            if ($ingredient->getUniteMesure() === $this) {
                $ingredient->setUniteMesure(null);
            }
        }

        return $this;
    }
}
