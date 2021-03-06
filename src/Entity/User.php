<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\AuthController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @ApiResource(
 *            
 *          collectionOperations={
 *              "get"={"access_control"= "is_granted('ROLE_ADMIN')"} ,
 *              "post"={"access_control"= "is_granted('ROLE_ADMIN') || is_granted('ROLE_PARTENAIRE') "} 
 *           },
 *           itemOperations={
 *              "get"= {"access_control"= "is_granted('VIEW', object)"} ,
 *              "put"= {"access_control"= "is_granted('EDIT', object)"},
 *              "delete"= {"access_control"= "is_granted('ROLE_SUPER_ADMIN')"} 
 *                
 *           },
 *          normalizationContext={"groups" = {"user_listing:read"}},
 *          denormalizationContext={"groups" = {"user_listing:write"}},         
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="users")
 */
class User implements AdvancedUserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user_listing:read","user_listing:write","partenaire:read","partenaire:write"})
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     * @Groups({"user_listing:read","user_listing:write","partenaire:read","partenaire:write"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     *
     */
    private $password;

    /**
     * @SerializedName("password")
     * @Groups({"user_listing:write"})
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="datetime")
     * 
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"user_listing:read","user_listing:write"})
     */
    private $isActive;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user_listing:read","user_listing:write","partenaire:read","partenaire:write"})
     */
    private $role;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Partenaire", inversedBy="users")
     * 
     * @Groups({"user_listing:read","user_listing:write", "partenaire:read"})
     */
    private $partenaire;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Affectations", mappedBy="user", orphanRemoval=true)
     */
    private $affectations;

    //private $encoder;

    public function isAccountNonExpired()
    {
        /*"put"= {"access_control"= "is_granted('ROLE_ADMIN') and object == user or is_granted('ROLE_ADMIN') and object.getRoles()[0] == 'ROLE_CAISSIER' or is_granted('ROLE_ADMIN') and object.getRoles()[0] == 'ROLE_ADMIN' or is_granted('ROLE_SUPER_ADMIN') "},*/
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    public function __construct($username = null)
    {
        $this->isActive = true;
        $this->username = $username;
        $this->createdAt = new \DateTime();
        $this->affectations = new ArrayCollection();
        //$this->encoder = $encoder;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
      //  $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        
       // $user = new User($this->encoder);
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }


    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

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
            $affectation->setUser($this);
        }

        return $this;
    }

    public function removeAffectation(Affectations $affectation): self
    {
        if ($this->affectations->contains($affectation)) {
            $this->affectations->removeElement($affectation);
            // set the owning side to null (unless already changed)
            if ($affectation->getUser() === $this) {
                $affectation->setUser(null);
            }
        }

        return $this;
    }

}
