<?php

namespace App\Entity;

use App\Repository\DeleteURepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=DeleteURepository::class)
 * @ORM\Table(name="t_delete_u")
 */
class DeleteU extends User
{
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $DTDeleted = null;

    public function getDTDeleted(): ?DateTime
    {
        return $this->DTDeleted;
    }

    public function setDateTime(?DateTime $DTDeleted): self
    {
        $this->DTDeleted = $DTDeleted;

        return $this;
    }
}
