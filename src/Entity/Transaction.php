<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * collectionOperations={
 *                  "get",
 *                  "post"={"route_name"="transaction", "method"="post","read"=true}
 *          },
 *              itemOperations={
 *                "get" ,
 *                "put",
 *                "delete"
 *              },
 *          normalizationContext={"groups" = {"transaction:read"}},
 *          denormalizationContext={"groups" = {"transaction:write"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 */
class Transaction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"transaction:read","transaction:write"})
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"transaction:read","transaction:write"})
     */
    private $codeEnvoie;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"transaction:read","transaction:write"})
     */
    private $montantTranfere;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compte", inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"transaction:read","transaction:write"})
     */
    private $compteSender;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compte", inversedBy="transactions")
     * @Groups({"transaction:read","transaction:write"})
     */
    private $compteRetrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"transaction:read","transaction:write"})
     */
    private $customerSender;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="transactionRetait")
     * @Groups({"transaction:read","transaction:write"})
     */
    private $customerRetrait;

    public function __construct()
    {
        $t = time();
        $this->createdAt = new \DateTime();
        $this->numero = date("ymHdis",$t);
        $this->codeEnvoie = date("His",$t);
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

    public function getCodeEnvoie(): ?string
    {
        return $this->codeEnvoie;
    }

    public function setCodeEnvoie(string $codeEnvoie): self
    {
        $this->codeEnvoie = $codeEnvoie;

        return $this;
    }

    public function getMontantTranfere(): ?int
    {
        return $this->montantTranfere;
    }

    public function setMontantTranfere(int $montantTranfere): self
    {
        $this->montantTranfere = $montantTranfere;

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

    public function getCompteSender(): ?Compte
    {
        return $this->compteSender;
    }

    public function setCompteSender(?Compte $compteSender): self
    {
        $this->compteSender = $compteSender;

        return $this;
    }

    public function getCompteRetrait(): ?Compte
    {
        return $this->compteRetrait;
    }

    public function setCompteRetrait(?Compte $compteRetrait): self
    {
        $this->compteRetrait = $compteRetrait;

        return $this;
    }

    public function getCustomerSender(): ?Customer
    {
        return $this->customerSender;
    }

    public function setCustomerSender(?Customer $customerSender): self
    {
        $this->customerSender = $customerSender;

        return $this;
    }

    public function getCustomerRetrait(): ?Customer
    {
        return $this->customerRetrait;
    }

    public function setCustomerRetrait(?Customer $customerRetrait): self
    {
        $this->customerRetrait = $customerRetrait;

        return $this;
    }
}
