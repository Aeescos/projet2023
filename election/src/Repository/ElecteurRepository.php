<?php

namespace App\Repository;

use App\Entity\CandidatPresidant;
use App\Entity\Electeur;
use App\Utils\Globals;
use App\Utils\ManifestHttp;
use App\Utils\UTILS;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Electeur>
 *
 * @method Electeur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Electeur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Electeur[]    findAll()
 * @method Electeur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ElecteurRepository extends ServiceEntityRepository
{
    private  Globals $globals;
    private $registry;
    
    public function __construct(ManagerRegistry $registry, Globals $globals)
    {
        parent::__construct($registry, Electeur::class);
        $this->globals = $globals;
        $this->registry = $registry;
    }

    public function registerUser( Object $data)
    {
            
        $myFormatObj = 'Y-m-d H:i:s';
        $dateTime = new DateTime();
        $dataCreate = $dateTime->format($myFormatObj);

        $isUser = $this->globals->em()->getRepository(Electeur::class)->findOneBy(['email' => $data->email]);

        if ( !$isUser) {
            $electeur = (new Electeur()) 
                ->setNom($data->nom)
                ->setPrenom($data->prenom)
                ->setEmail($data->email)
                ->setDTCreate($dataCreate)
                ->setAdderer(false)
                ->setAddress($data->address)
                ->setDTNaissance($data->naissance)
                ->setFormation($data->formation)
                ->setIdDateCreate($this->getIdDateCreate())
                ->setLieuxnaissance($data->lieuNaissance)
                ->setTelephone($data->telephone)
                ;
    
            $this->globals->em()->persist($electeur);
            $this->globals->em()->flush();
            $this->globals->setImageFirebaseStorage();

            return UTILS::ADD;
        }

        return UTILS::ERROR;
        
    }

    public function delete(Electeur $user, bool $flush = false)
    {
        $this->globals->em()->remove($user);

        if ($flush) {
            $this->globals->em()->flush();
            return $this->globals->success([
                "User" => $user->getEmail()." Is deleted"
            ]);
        }
        return $this->globals->error(ManifestHttp::FROM_ERROR);
    }

    public function getAll(array $criteria = [])
    {
        return $this->findAll($criteria);
    }

    public function update( Object $data, string $telephone)
    {
        $userElecte = $this->globals->em()->getRepository(Electeur::class)->findOneBy(['email' => $data->email]);
        if ($userElecte) {
            $userElecte                
                ->setNom($data->nom)
                ->setPrenom($data->prenom)
                ->setEmail($data->email)
                ->setDTCreate($data->dataCreate)
                ->setAdderer($data->adderer)
                ->setAddress($data->address)
                ->setDTNaissance($data->naissance)
                ->setFormation($data->formation)
                ->setIdDateCreate($data->idDateCreate)
                ->setLieuxnaissance($data->lieuNaissance)
                ->setTelephone($data->telephone);
            $this->globals->em()->flush();
                
            return $this->globals->success([
                "Electeur" => $userElecte->getEmail()." Is voted"
            ]);
        }

        return $this->globals->error(ManifestHttp::FROM_ERROR);
    }


    public function voterPresidant( Object $data, string $telephone)
    {
        $candidatPre = $this->globals->em()->getRepository(CandidatPresidant::class)->findOneBy(['telephone' => strtoupper($telephone)]);
        $userElecte = $this->globals->em()->getRepository(Electeur::class)->findOneBy(['email' => $data->email]);
        if ($candidatPre && $userElecte) {
            $userElecte->addCandidatPresidant($candidatPre);
            $this->globals->em()->flush();
                
            return $this->globals->success([
                "Electeur" => $userElecte->getEmail()." Is voted"
            ]);
        }

        return $this->globals->error(ManifestHttp::FROM_ERROR);
    }



    public function getById($id)
    {
        return $this->find($id) ? $this->find($id)->ListeElecteur() : null;
    }

    private function getIdDateCreate(): string
    {
        //Laste id User à la base de donné
        $userLastId = $this->registry->getConnection()->lasteInsertId();
        // Formater le nombre en une chaine à 4 chiffre
        $nombreStr = str_pad($userLastId, 4, STR_PAD_LEFT);
        // Recuperer la date courante
        $currentYear = date("y");
        // Chaine "ASC" fixe
        $asc = "ASC";
        //Generer la chaine ID unique de l'utilisateur avec format ASC/date/nombreStr
        return $asc."/".$currentYear."/".$nombreStr;
    }
}
