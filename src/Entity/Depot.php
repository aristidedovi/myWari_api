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
     * 
     */
    private $mntDeposser;

    /**
     * @ORM\Column(type="datetime")
     * 
     */
    private $deposser_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compte", inversedBy="depots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $compte;

    public function __construct()
    {
        $this->deposser_at = new \DateTime();
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
        return $this->deposser_at;
    }

    public function setDeposserAt(\DateTimeInterface $deposser_at): self
    {
        $this->deposser_at = $deposser_at;

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
