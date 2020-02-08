<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ApiResource(
 * collectionOperations={
 *                  "get"= {"access_control"= "is_granted('ROLE_ADMIN')"} ,
 *                  "post"={"route_name"="creation_compte", "method"="post", "read"=true}
 *          },
 *              itemOperations={
 *                "get"= {"access_control"= "is_granted('COMPTE_VIEW', object)"} ,
 *                "put"= {"access_control"= "is_granted('COMPTE_EDIT', object)"},
 *                "delete"= {"access_control"= "is_granted('ROLE_ADMIN')"}
 *              },
 *          normalizationContext={"groups" = {"compte:read"}},
 *          denormalizationContext={"groups" = {"compte:write"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CompteRepository")
 * 
 */
class Compte
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"partenaire:read","partenaire:write","compte:read","compte:write"})
     * 
     */
    private $numero;

    /**
     * @ORM\Column(type="float")
     * @Groups({"partenaire:read","partenaire:write","compte:read","compte:write"})
     * 
     */
    private $solde;

    /**
     * @ORM\Column(type="datetime")
     * 
     */
    private $create_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Partenaire", inversedBy="comptes")
     * @ORM\JoinColumn(nullable=false)
     * @ApiSubresource()
     * @Groups({"compte:read","compte:write"})
     */
    private $partenaire;

    /**
     * @ApiSubresource()
     * @ORM\OneToMany(targetEntity="App\Entity\Depot", mappedBy="compte", orphanRemoval=true)
     * @Groups({"partenaire:read","partenaire:write","compte:read","compte:write"})
     */
    private $depots;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Affectations", mappedBy="compte", orphanRemoval=true)
     */
    private $affectations;

    public function __construct()
    {
        $this->depots = new ArrayCollection();
        $this->create_at = new \DateTime();
        $this->solde = 0;
        $this->affectations = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getSolde(): ?float
    {
        return $this->solde;
    }

    public function setSolde(float $solde): self
    {
        $this->solde = $solde;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->create_at;
    }

    public function setCreateAt(\DateTimeInterface $create_at): self
    {
        $this->create_at = $create_at;

        return $this;
    }

    public function getPartenaire(): ?Partenaire
    {
        return $this->partenaire;
    }

    public function setPartenaire(?Partenaire $partenaire): self
    {
        $this->partenaire = $partenaire;

        return $this;
    }

    /**
     * @return Collection|Depot[]
     */
    public function getDepots(): Collection
    {
        return $this->depots;
    }

    public function addDepot(Depot $depot): self
    {
        if (!$this->depots->contains($depot)) {
            $this->depots[] = $depot;
            $depot->setCompte($this);
        }

        return $this;
    }

    public function removeDepot(Depot $depot): self
    {
        if ($this->depots->contains($depot)) {
            $this->depots->removeElement($depot);
            // set the owning side to null (unless already changed)
            if ($depot->getCompte() === $this) {
                $depot->setCompte(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Affectations[]
     */
    public function getAffectations(): Collection
    {
        return $this->affectations;
    }

    public function addAffectation(Affectations $affectation): self
    {
        if (!$this->affectations->contains($affectation)) {
            $this->affectations[] = $affectation;
            $affectation->setCompte($this);
        }

        return $this;
    }

    public function removeAffectation(Affectations $affectation): self
    {
        if ($this->affectations->contains($affectation)) {
            $this->affectations->removeElement($affectation);
            // set the owning side to null (unless already changed)
            if ($affectation->getCompte() === $this) {
                $affectation->setCompte(null);
            }
        }

        return $this;
    }
}
