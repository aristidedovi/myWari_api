<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\AuthController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ApiResource(
 *            
 *          collectionOperations={
 *              "get"={"access_control"= "is_granted('ROLE_ADMIN')"} ,
 *              "post"={"access_control"= "is_granted('ROLE_ADMIN')"} 
 *           },
 *           itemOperations={
 *              "get"= {"access_control"= "is_granted('ROLE_CAISSIER') and object == user or is_granted('ROLE_ADMIN') and object == user or is_granted('ROLE_ADMIN') and object.getRoles()[0] == 'ROLE_CAISSIER' or is_granted('ROLE_ADMIN') and object.getRoles()[0] == 'ROLE_ADMIN' or is_granted('ROLE_SUPER_ADMIN')"} ,
 *              "put"= {"access_control"= "is_granted('ROLE_ADMIN') and object == user or is_granted('ROLE_ADMIN') and object.getRoles()[0] == 'ROLE_CAISSIER' or is_granted('ROLE_ADMIN') and object.getRoles()[0] == 'ROLE_ADMIN' or is_granted('ROLE_SUPER_ADMIN') "},
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
     * @Groups({"user_listing:read","user_listing:write"})
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     * @Groups({"user_listing:read","user_listing:write"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"user_listing:write"})
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     *  @Groups({"user_listing:read","user_listing:write"})
     */
    private $isActive;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user_listing:read","user_listing:write"})
     */
    private $role;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Partenaire", mappedBy="user", cascade={"persist", "remove"})
     */
    private $partenaire;

    //private $encoder;

    public function isAccountNonExpired()
    {
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

    public function __construct($username)
    {
        $this->isActive = true;
        $this->username = $username;
        $this->createdAt = new \DateTime();
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

    public function getPartenaire(): ?Partenaire
    {
        return $this->partenaire;
    }

    public function setPartenaire(Partenaire $partenaire): self
    {
        $this->partenaire = $partenaire;

        // set the owning side of the relation if necessary
        if ($partenaire->getUser() !== $this) {
            $partenaire->setUser($this);
        }

        return $this;
    }
}
