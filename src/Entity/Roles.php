<?php

namespace App\Entity;

use App\Repository\RolesRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass=RolesRepository::class)
 * @ORM\Table(name="t_roles")
 */
class Roles
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private ?string $rolesEmp = null;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="roles")
     */
    private Collection $users;



    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRolesEmp(): ?string
    {
        return $this->rolesEmp;
    }

    public function setRolesEmp(?string $rolesEmp): self
    {
        $this->rolesEmp = $rolesEmp;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addRole($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeRole($this);
        }

        return $this;
    }

    //--------------------------------------------------------

    //--------------------------------------------------------
    public function ListeRoles(): array
    {
        return [
            "id" => $this->id,
            "rolesEmp" => $this->rolesEmp,
        ];
    }
    
}

?>
