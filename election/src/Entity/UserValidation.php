<?php

namespace App\Entity;

use App\Repository\UserValidationRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use DateTimeInterface;

/**
 * @ORM\Entity(repositoryClass=UserValidationRepository::class)
 * @ORM\Table(name="t_user_validation")
 */
class UserValidation 
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
    private ?string $codeValidation = null;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private ?string $email = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $DExpire = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $TExpire = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeValidation(): ?string
    {
        return $this->codeValidation;
    }

    public function setCodeValidation(?string $codeValidation): self
    {
        $this->codeValidation = $codeValidation;

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

    public function getDExpire(): ?string
    {
        return $this->DExpire;
    }

    public function setDExpire(?string $DExpire): self
    {
        $this->DExpire = $DExpire;

        return $this;
    }

    public function getTExpire(): ?string
    {
        return $this->TExpire;
    }

    public function setTExpire(?string $TExpire): self
    {
        $this->TExpire = $TExpire;

        return $this;
    }


    public function ListeUser(): array
    {
        return [
            "id" => $this->id,
            "codeValidation" => $this->codeValidation,
            "email" => $this->email,
            "DExpire" => $this->DExpire,
            "TExpire" => $this->TExpire,
        ];
    }

}
