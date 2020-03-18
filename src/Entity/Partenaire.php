<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *          collectionOperations={
 *              "get",
 *              "post"={"access_control"= "is_granted('ROLE_ADMIN_SYSTEME')"}
 *           },
 *           itemOperations={
 *              "get"= {"access_control"= "is_granted('PARTENAIRE_VIEW', object)"} ,
 *              "put"= {"access_control"= "is_granted('PARTENAIRE_EDIT', object)"},
 *              "delete"= {"access_control"= "is_granted('ROLE_ADMIN_SYSTEME')"}
 *           },
 *          normalizationContext={"groups" = {"partenaire:read"}},
 *          denormalizationContext={"groups" = {"partenaire:write"}},
 *          )
 * @ORM\Entity(repositoryClass="App\Repository\PartenaireRepository")
 */
class Partenaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"user_listing:read","user_listing:write","partenaire:read","partenaire:write","compte:read","compte:write"})
     *
     */
    private $ninea;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user_listing:read","user_listing:write","partenaire:read","partenaire:write","compte:read","compte:write"})
     */
    private $rc;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Compte", mappedBy="partenaire", orphanRemoval=true)
     * @Groups({"partenaire:read","partenaire:write","compte:read","compte:write"})
     */
    private $comptes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="partenaire")
     * @ApiSubresource()
     * @Groups({"partenaire:read","partenaire:write"})
     */
    private $users;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"partenaire:read","partenaire:write"})
     */
    private $isActive;


    public function __construct()
    {
        $this->comptes = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->isActive = true;
       
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNinea(): ?string
    {
        return $this->ninea;
    }

    public function setNinea(string $ninea): self
    {
        $this->ninea = $ninea;

        return $this;
    }

    public function getRc(): ?string
    {
        return $this->rc;
    }

    public function setRc(string $rc): self
    {
        $this->rc = $rc;

        return $this;
    }

    /**
     * @return Collection|Compte[]
     */
    public function getComptes(): Collection
    {
        return $this->comptes;
    }

    public function addCompte(Compte $compte): self
    {
        if (!$this->comptes->contains($compte)) {
            $this->comptes[] = $compte;
            $compte->setPartenaire($this);
        }

        return $this;
    }

    public function removeCompte(Compte $compte): self
    {
        if ($this->comptes->contains($compte)) {
            $this->comptes->removeElement($compte);
            // set the owning side to null (unless already changed)
            if ($compte->getPartenaire() === $this) {
                $compte->setPartenaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setPartenaire($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getPartenaire() === $this) {
                $user->setPartenaire(null);
            }
        }

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }


}
