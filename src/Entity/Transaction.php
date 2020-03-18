<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
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
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $codeEnvoie;

    /**
     * @ORM\Column(type="integer")
     */
    private $montantTranfere;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

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
}
