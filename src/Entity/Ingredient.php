<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
#[ORM\Table(name: 'ingredient')]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_ingredient', type: 'integer')]
    #[Groups(['ingredient:read'])]
    private ?int $id = null;

    #[ORM\Column(name: 'nom_ingredient', type: 'string', length: 100)]
    #[Groups(['ingredient:read'])]
    private string $nomIngredient;

    #[ORM\Column(name: 'url', type: 'string', length: 255, nullable: true)]
    #[Groups(['ingredient:read'])]
    private ?string $imgUrl = null;

    #[ORM\Column(name: 'logo', type: 'string', length: 255, nullable: true)]
    #[Groups(['ingredient:read'])]
    private ?string $logo = null;

    #[ORM\ManyToOne(targetEntity: UniteMesure::class)]
    #[ORM\JoinColumn(name: 'id_unite_mesure', referencedColumnName: 'id_unite_mesure', nullable: false)]
    #[MaxDepth(1)] //#[Ignore]
    private UniteMesure $uniteMesure;

    #[ORM\OneToMany(mappedBy: 'ingredient', targetEntity: StockIngredient::class)]
    private Collection $ingredients;

    public function __construct()
    {
        $this->stockIngredients = new ArrayCollection();
    }

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomIngredient(): string
    {
        return $this->nomIngredient;
    }

    public function setNomIngredient(string $nomIngredient): self
    {
        $this->nomIngredient = $nomIngredient;
        return $this;
    }

    public function getImgUrl(): ?string
    {
        return $this->imgUrl;
    }

    public function setImgUrl(?string $imgUrl): self
    {
        $this->imgUrl = $imgUrl;
        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;
        return $this;
    }

    public function getUniteMesure(): ?UniteMesure
    {
        return $this->uniteMesure;
    }

    public function setUniteMesure(UniteMesure $uniteMesure): self
    {
        $this->uniteMesure = $uniteMesure;
        return $this;
    }

    public function getStockIngredients(): Collection
    {
        return $this->stockIngredients;
    }

    public function addStockIngredient(StockIngredient $stockIngredient): self
    {
        if (!$this->stockIngredients->contains($stockIngredient)) {
            $this->stockIngredients[] = $stockIngredient;
            $stockIngredient->setUniteMesure($this);
        }

        return $this;
    }

    public function removeIngredient(StockIngredient $stockIngredient): self
    {
        if ($this->stockIngredients->removeElement($stockIngredient)) {
            if ($stockIngredient->getUniteMesure() === $this) {
                $stockIngredient->setUniteMesure(null);
            }
        }

        return $this;
    }
}
