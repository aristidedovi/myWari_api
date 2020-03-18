<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
  *          collectionOperations={
 *              "get"={"access_control"= "is_granted('ROLE_PARTENAIRE')"} ,
 *              "post"={"access_control"= "is_granted('ROLE_PARTENAIRE') or is_granted('ROLE_ADMIN_PARTENAIRE')"}
 *           },
 *           itemOperations={
 *              "get"= {"access_control"= "is_granted('ROLE_PARTENAIRE')"} ,
 *              "put"= {"access_control"= "is_granted('ROLE_PARTENAIRE')"},
 *              "delete"= {"access_control"= "is_granted('ROLE_PARTENAIRE')"}
 *           },
 * )
 * @ORM\Entity(repositoryClass="App\Repository\AffectationsRepository")
 */
class Affectations
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * 
     */
    private $affecterStartAt;

    /**
     * @ORM\Column(type="datetime")
     * 
     */
    private $affecterEndAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="affectations")
     * @ORM\JoinColumn(nullable=false)
     * 
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compte", inversedBy="affectations")
     * @ORM\JoinColumn(nullable=false)
     * 
     */
    private $compte;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAffecterAt(): ?\DateTimeInterface
    {
        return $this->affecterStartAt;
    }

    public function setAffecterAt(\DateTimeInterface $affecterStartAt): self
    {
        $this->affecterStartAt = $affecterStartAt;

        return $this;
    }

    public function getAffecterEndAt(): ?\DateTimeInterface
    {
        return $this->affecterEndAt;
    }

    public function setAffecterEndAt(\DateTimeInterface $affecterEndAt): self
    {
        $this->affecterEndAt = $affecterEndAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
