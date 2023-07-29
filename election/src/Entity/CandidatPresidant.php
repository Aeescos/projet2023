<?php

namespace App\Entity;

use App\Repository\CandidatPresidantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=CandidatPresidantRepository::class)
 * @ORM\Table(name="t_CandidatPresidant")
 */
class CandidatPresidant extends Electeur
{

    /**
     * @ORM\ManyToMany(targetEntity=Electeur::class, mappedBy="roles")
     */
    private Collection $electeur;


    public function __construct()
    {
        $this->electeur = new ArrayCollection();
    }

    /**
     * @return Collection<int, Electeur>
     */
    public function getElecteur(): Collection
    {
        return $this->electeur;
    }

    public function addElecteur(Electeur $electeur): self
    {
        if (!$this->electeur->contains($electeur)) {
            $this->electeur->add($electeur);
            $electeur->addCandidatPresidant($this);
        }

        return $this;
    }

    public function removeElecteur(Electeur $electeur): self
    {
        if ($this->electeur->removeElement($electeur)) {
            $electeur->removeCandidatPresidant($this);
        }

        return $this;
    }
   
}

