<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ApiResource(
 *  *          collectionOperations={
 *              "get",
 *              "post"
 *           },
 *           itemOperations={
 *              "get",
 *              "put",
 *              "delete"
 *           },
 *          normalizationContext={"groups" = {"role:read"}},
 *          denormalizationContext={"groups" = {"role:write"}},
 * )
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 */
class Role
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"role:read","role:write","user_listing:read","user_listing:write"})
     */
    private $id;

    /**
     * @Groups({"user_listing:read"})
     * @ORM\Column(type="string", length=100)
     * @Groups({"role:read","role:write","user_listing:read","user_listing:write"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="role", orphanRemoval=true)
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

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
            $user->setRole($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getRole() === $this) {
                $user->setRole(null);
            }
        }

        return $this;
    }
}
