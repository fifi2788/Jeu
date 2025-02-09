<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
#[ORM\Table(name: 'commande')]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_commande', type: 'integer')]
    #[Groups(['commande:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Plat::class)]
    #[ORM\JoinColumn(name: 'id_plat', referencedColumnName: 'id_plat', nullable: false)]
    #[MaxDepth(1)] //#[Ignore]
    private Plat $plat;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: 'id_client', referencedColumnName: 'id_utilisateur', nullable: false)]
    #[MaxDepth(1)] //#[Ignore]
    private Utilisateur $client;

    #[ORM\Column(name: 'quantite_plat', type: 'integer')]
    #[Groups(['commande:read'])]
    private ?int $quantitePlat = 1;

    #[ORM\Column(name: 'etat', type: 'integer', nullable: true)]
    #[Groups(['commande:read'])]
    private ?int $etat = 0;

    #[ORM\Column(name: 'date_heure_commande', type: 'datetime')]
    #[Groups(['commande:read'])]
    private datetime $dateHeureCommande;

    #[ORM\Column(name: 'date_heure_livraison', type: 'datetime')]
    #[Groups(['commande:read'])]
    private datetime $dateHeureLivraison;

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantitePlat(): ?int
    {
        return $this->quantite_plat;
    }

    public function setQuantitePlat(?int $quantitePlat): self
    {
        $this->quantite_plat = $quantitePlat;
        return $this;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(?int $etat): self
    {
        $this->etat = $etat;
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

    public function getPlat(): ?Plat
    {
        return $this->plat;
    }

    public function setPlat(Plat $plat): self
    {
        $this->plat = $plat;
        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->client;
    }

    public function setUtilisateur(Utilisateur $client): self
    {
        $this->client = $client;
        return $this;
    }

    public function getDateHeureCommande(): ?datatime
    {
        return $this->dateHeureCommande;
    }

    public function setDateHeureCommande(?datetime $dateHeureCommande): self
    {
        $this->dateHeureCommande = $dateHeureCommande;
        return $this;
    }

    public function getDateLivraison(): ?datatime
    {
        return $this->dateHeureLivraison;
    }

    public function setDateHeureLivraison(?datetime $dateHeureLivraison): self
    {
        $this->dateHeureLivraison = $dateHeureLivraison;
        return $this;
    }

}
