<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiSubresource;

/**
 * @ApiResource(
 * collectionOperations={
 *                  "get"= {"access_control"= "is_granted('ROLE_CAISSIER')"} ,
 *                  "post"={"access_control"= "is_granted('ROLE_CAISSIER')"}
 *          },
 *              itemOperations={
 *                "get"= {"access_control"= "is_granted('ROLE_CAISIIER')"} ,
 *                "put"= {"access_control"= "is_granted('ROLE_CAISSIER')"},
 *                "delete"= {"access_control"= "is_granted('ROLE_ADMIN')"}
 *              },
 *          normalizationContext={"groups" = {"depot:read"}},
 *          denormalizationContext={"groups" = {"depot:write"}}
 *          
 * )
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
     * @Groups({"partenaire:read","partenaire:write","depot:read","depot:write"})
     */
    private $mntDeposser;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"partenaire:read","partenaire:write","depot:read","depot:write"})
     */
    private $deposserAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compte", inversedBy="depots")
     * @ORM\JoinColumn(nullable=false)
     *  @ApiSubresource()
     * @Groups({"depot:read","depot:write"})
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
