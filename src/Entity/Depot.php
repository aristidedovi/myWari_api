<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\DepotRepository")
 */
class Depot
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"partenaire:read","partenaire:write"})
     */
    private $mntDeposser;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"partenaire:read","partenaire:write"})
     */
    private $deposserAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compte", inversedBy="depots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $compte;

    public function __construct()
    {
        $this->deposserAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMntDeposser(): ?int
    {
        return $this->mntDeposser;
    }

    public function setMntDeposser(int $mntDeposser): self
    {
        $this->mntDeposser = $mntDeposser;

        return $this;
    }

    public function getDeposserAt(): ?\DateTimeInterface
    {
        return $this->deposserAt;
    }

    public function setDeposserAt(\DateTimeInterface $deposserAt): self
    {
        $this->deposserAt = $deposserAt;

        return $this;
    }

    public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    public function setCompte(?Compte $compte): self
    {
        $this->compte = $compte;

        return $this;
    }
}
