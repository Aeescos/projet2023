<?php

namespace App\Entity;

use App\Repository\ElecteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ElecteurRepository::class)
 * @ORM\Table(name="t_electeur")
 */
class Electeur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;
    
    /**
     * @Assert\NotBlank(groups="Le champ ne doit pas être vide")
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private ?string $idDateCreate = null;

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
    private ?string $telephone = null;


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $dTNaissance = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?String $lieuxnaissance = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?String $address = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?String $formation = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $DtCreate = null;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $adderer = null;

    
    /**
     * @ORM\ManyToMany(targetEntity=Roles::class, inversedBy="users")
     */
    private Collection $candidatPresident;


    public function __construct()
    {
        $this->candidatPresident = new ArrayCollection();
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


    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setIdDateCreate(?string $idDateCreate): self
    {
        $this->idDateCreate = $idDateCreate;

        return $this;
    }

    public function getIdDateCreate(): ?string
    {
        return $this->idDateCreate;
    }
    
    public function setDTNaissance(?string $dTNaissance): self
    {
        $this->dTNaissance = $dTNaissance;

        return $this;
    }

    public function getDTNaissance(): ?string
    {
        return $this->dTNaissance;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }
    

    //------------------------------------
    /**
     * @return string[]
     */
    public function getCandidatPresidant(): array
    {
        $candidat = $this->candidatPresident->toArray();
        $prefixedcandidat = array_map(function ($candidat) {
            return 'CAND_' . $candidat->getTelephone();
        }, $candidat);

        return $prefixedcandidat;
    }

    public function setCandidatPresidant(array $candidats): self
    {
        // Vider les rôles existants
        $this->candidatPresident->clear();

        // Ajouter les nouveaux rôles
        foreach ($candidats as $candidat) {
            $this->addCandidatPresidant($candidat);
        }

        return $this;
    }

    public function addCandidatPresidant(CandidatPresidant $candidat): self
    {
        if (!$this->candidatPresident->contains($candidat)) {
            $this->candidatPresident[] = $candidat;
        }

        return $this;
    }

    public function removeCandidatPresidant(CandidatPresidant $candidat): self
    {
        $this->candidatPresident->removeElement($candidat);

        return $this;
    }
    //------------------------------------------------


    public function isAdderer(): ?bool
    {
        return $this->adderer;
    }

    public function setAdderer(?bool $adderer): self
    {
        $this->adderer = $adderer;

        return $this;
    }


    public function getDTCreate(): ?string
    {
        return $this->DtCreate;
    }

    public function setDTCreate(?string $DtCreate): self
    {
        $this->DtCreate = $DtCreate;

        return $this;
    }

    public function getFormation(): ?string
    {
        return $this->formation;
    }

    public function setFormation(?string $formation): self
    {
        $this->formation = $formation;

        return $this;
    }

    public function getLieuxnaissance(): ?string
    {
        return $this->lieuxnaissance;
    }

    public function setLieuxnaissance(?string $lieuxnaissance): self
    {
        $this->lieuxnaissance = $lieuxnaissance;

        return $this;
    }


    //----------------------------------------------------------
    public function ListeElecteur(): array
    {
        return [
            "id" => $this->id,
            "nom" => $this->nom,
            "prenom" => $this->prenom,
            "email" => $this->email,
            "telephone" => $this->telephone,
            "adderer" => $this->adderer,
            "formation" => $this->formation,
            "lieuxnaissance" => $this->lieuxnaissance,
            "DTCreate" => $this->DtCreate,
            "address" => $this->address,
            "dTNaissance" => $this->dTNaissance,
            "idDateCreate" => $this->idDateCreate,
        ];
    }
}

