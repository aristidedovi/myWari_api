<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * collectionOperations={
 *                  "get",
 *                  "post"
 *          },
 *              itemOperations={
 *                "get" ,
 *                "put",
 *                "delete"
 *              },
 *          normalizationContext={"groups" = {"customer:read"}},
 *          denormalizationContext={"groups" = {"customer:write"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 */
class Customer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"customer:read","customer:write","transaction:read","transaction:write"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=100)
     *  @Groups({"customer:read","customer:write","transaction:read","transaction:write"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     *  @Groups({"customer:read","customer:write","transaction:read","transaction:write"})
     */
    private $genre;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     *  @Groups({"customer:read","customer:write","transaction:read","transaction:write"})
     */
    private $identityCard;

    /**
     * @ORM\Column(type="string", length=100,nullable=true)
     *  @Groups({"customer:read","customer:write","transaction:read","transaction:write"})
     */
    private $typeIdentityCard;

    /**
     * @ORM\Column(type="string", length=100)
     *  @Groups({"customer:read","customer:write","transaction:read","transaction:write"})
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=100,nullable=true)
     *  @Groups({"customer:read","customer:write","transaction:read","transaction:write"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="customerSender", orphanRemoval=true)
     */
    private $transactions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="customerRetrait")
     */
    private $transactionRetait;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
        $this->transactionRetait = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getIdentityCard(): ?string
    {
        return $this->identityCard;
    }

    public function setIdentityCard(string $identityCard): self
    {
        $this->identityCard = $identityCard;

        return $this;
    }

    public function getTypeIdentityCard(): ?string
    {
        return $this->typeIdentityCard;
    }

    public function setTypeIdentityCard(string $typeIdentityCard): self
    {
        $this->typeIdentityCard = $typeIdentityCard;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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
            $transaction->setCustomerSender($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->contains($transaction)) {
            $this->transactions->removeElement($transaction);
            // set the owning side to null (unless already changed)
            if ($transaction->getCustomerSender() === $this) {
                $transaction->setCustomerSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactionRetait(): Collection
    {
        return $this->transactionRetait;
    }

    public function addTransactionRetait(Transaction $transactionRetait): self
    {
        if (!$this->transactionRetait->contains($transactionRetait)) {
            $this->transactionRetait[] = $transactionRetait;
            $transactionRetait->setCustomerRetrait($this);
        }

        return $this;
    }

    public function removeTransactionRetait(Transaction $transactionRetait): self
    {
        if ($this->transactionRetait->contains($transactionRetait)) {
            $this->transactionRetait->removeElement($transactionRetait);
            // set the owning side to null (unless already changed)
            if ($transactionRetait->getCustomerRetrait() === $this) {
                $transactionRetait->setCustomerRetrait(null);
            }
        }

        return $this;
    }
}
