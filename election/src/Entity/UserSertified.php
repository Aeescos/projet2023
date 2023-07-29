<?php

namespace App\Entity;

use App\Repository\UserSertifiedRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

/**
 * @ORM\Entity(repositoryClass=UserSertifiedRepository::class)
 * @ORM\Table(name="t_user_sertified")
 */
class UserSertified 
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private ?string $email = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $DSertified = null;




    public function getId(): ?int
    {
        return $this->id;
    }

    //
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    //
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }


    //
    public function getDSertified(): ?string
    {
        return $this->DSertified;
    }

    public function setDSertified(?string $DSertified): self
    {
        $this->DSertified = $DSertified;

        return $this;
    }

    //
    public function ListeUser(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "DSertified" => $this->DSertified,
        ];
    }

}
