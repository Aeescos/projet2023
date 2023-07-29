<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Ignore;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="t_user")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @Assert\NotBlank(groups="Le champ ne doit pas être vide")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $nom = null;

    /**
     * @Assert\NotBlank(groups="Le champ ne doit pas être vide")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $prenom = null;

    /**
     * @Assert\NotBlank(groups="Le champ ne doit pas être vide")
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private ?string $email = null;

    /**
     * @Assert\NotBlank(groups="Le champ ne doit pas être vide")
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private ?string $imageUser = null;

    /**
     * @Assert\NotBlank(groups="Le champ ne doit pas être vide")
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private ?string $nineUser = null;

    /**
     * @Assert\NotBlank(groups="Le champ ne doit pas être vide")
     * @ORM\Column(type="string", length=300, nullable=true)
     * @Ignore
     */
    private ?string $passe_mot = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $DTCreate = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $DTSertified = null;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $active;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $sertified;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $bloquer;

    /**
     * @ORM\ManyToMany(targetEntity=Roles::class, inversedBy="users")
     */
    private Collection $roles;


    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getSalt()
    {
        // Retourne le sel utilisé pour encoder le mot de passe (bcrypt ne nécessite pas de sel séparé)
        // Vous pouvez simplement retourner null si vous utilisez bcrypt
        return null;
    }

    public function getUsername(): string
    {
        // Retourne l'identifiant unique de l'utilisateur (par exemple, l'email ou le nom d'utilisateur)
        return (string) $this->email; // Remplacez "email" par le champ approprié dans votre entité User
    }

    public function setUsername(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->passe_mot;
    }

    public function setPassword(string $password): self
    {
        $this->passe_mot = $password;

        return $this;
    }


    //------------------------------------
    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        $roles = $this->roles->toArray();
        $prefixedRoles = array_map(function ($role) {
            return 'ROLE_' . $role->getRolesEmp();
        }, $roles);

        return $prefixedRoles;
    }

    public function setRoles(array $roles): self
    {
        // Vider les rôles existants
        $this->roles->clear();

        // Ajouter les nouveaux rôles
        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    public function addRole(Roles $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(Roles $role): self
    {
        $this->roles->removeElement($role);

        return $this;
    }
    //------------------------------------------------

    public function eraseCredentials()
    {
        // Laisser cette méthode vide
    }

    public function serialize(): string
    {
        return serialize([$this->id, $this->email]);
    }

    public function unserialize($serialized)
    {
        [$this->id, $this->email] = unserialize($serialized, ['allowed_classes' => false]);
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    
    public function isSertified(): ?bool
    {
        return $this->sertified;
    }

    public function setSertified(?bool $sertified): self
    {
        $this->sertified = $sertified;

        return $this;
    }

    public function isBloquer(): ?bool
    {
        return $this->bloquer;
    }

    public function setBloquer(?bool $bloquer): self
    {
        $this->bloquer = $bloquer;

        return $this;
    }

    public function getPasseMot(): ?string
    {
        return $this->passe_mot;
    }

    public function setPasseMot(?string $passe_mot): static
    {
        $this->passe_mot = $passe_mot;

        return $this;
    }

    public function getDTCreate(): ?string
    {
        return $this->DTCreate;
    }

    public function setDTCreate(?string $DTCreate): self
    {
        $this->DTCreate = $DTCreate;

        return $this;
    }

    public function getDTSertified(): ?string
    {
        return $this->DTSertified;
    }

    public function setDTSertified(?string $DTSertified): self
    {
        $this->DTSertified = $DTSertified;

        return $this;
    }

    public function getNineUser(): ?string
    {
        return $this->nineUser;
    }

    public function setNineUser(?string $nineUser): self
    {
        $this->nineUser = $nineUser;

        return $this;
    }

    public function getImageUser(): ?string
    {
        return $this->imageUser;
    }

    public function setImageUser(?string $imageUser): self
    {
        $this->imageUser = $imageUser;

        return $this;
    }


    public function ListeUser(): array
    {
        return [
            "id" => $this->id,
            "nom" => $this->nom,
            "prenom" => $this->prenom,
            "email" => $this->email,
            "password" => $this->passe_mot,
            "active" => $this->active,
        ];
    }
}

