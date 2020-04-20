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
 *                  "get",
 *                  "post"={"route_name"="creation_compte", "method"="post","read"=true}
 *          },
 *              itemOperations={
 *                "get"= {"access_control"= "is_granted('COMPTE_VIEW', object)"} ,
 *                "put"= {"access_control"= "is_granted('COMPTE_EDIT', object)"},
 *                "delete"= {"access_control"= "is_granted('ROLE_ADMIN_SYSTEME')"}
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
     * @Groups({"partenaire:read","partenaire:write","compte:read","compte:write","depot:read","depot:write"})
     *
     */
    private $numero;

    /**
     * @ORM\Column(type="float")
     * @Groups({"partenaire:read","partenaire:write","compte:read","compte:write","depot:read","depot:write"})
     *
     */
    private $solde;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"partenaire:read","partenaire:write","compte:read","compte:write"})
     */
    private $createAt;

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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="compteSender", orphanRemoval=true)
     */
    private $transactions;

    public function __construct()
    {
        $this->depots = new ArrayCollection();
        $this->createAt = new \DateTime();
        $this->solde = 0;
        $this->affectations = new ArrayCollection();
        $this->transactions = new ArrayCollection();

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
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

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

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setCompteSender($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->contains($transaction)) {
            $this->transactions->removeElement($transaction);
            // set the owning side to null (unless already changed)
            if ($transaction->getCompteSender() === $this) {
                $transaction->setCompteSender(null);
            }
        }

        return $this;
    }
}
