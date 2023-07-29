<?php

namespace App\Repository;

use App\Entity\Roles;
use App\Entity\User;
use App\Utils\Globals;
use App\Utils\ManifestHttp;
use App\Utils\UTILS;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    private  Globals $globals;
    
    
    public function __construct(ManagerRegistry $registry, Globals $globals)
    {
        parent::__construct($registry, User::class);
        $this->globals = $globals;
    }

    public function registerUser( Object $data)
    {
            
        $myFormatObj = 'Y-m-d H:i:s';
        $dateTime = new DateTime();
        $dataCreate = $dateTime->format($myFormatObj);

        $role = $this->globals->em()->getRepository(Roles::class)->findOneBy(['rolesEmp' => strtoupper($data->roleUser)]);
        $isUser = $this->globals->em()->getRepository(User::class)->findOneBy(['email' => $data->email]);

        if ($role && !$isUser) {
            $user = (new User()) 
                ->setNom($data->nom)
                ->setPrenom($data->prenom)
                ->setActive(false)
                ->setEmail($data->email)
                ->setDTCreate( $dataCreate )
                ->setDTSertified(null)
                ->setNineUser($data->nineUser)
                ->setSertified(false)
                ->setBloquer(false)
                ->setImageUser($data->imageUser)
                ->setImagePasportUser($data->imagePasportUser)
                ->addRole($role);
            $user->setPassword($this->globals->hasher()->hashPassword($user, $data->password));
    
            $this->globals->em()->persist($user);
            $this->globals->em()->flush();
            $this->globals->setImageFirebaseStorage();

            return UTILS::ADD;
        }

        return UTILS::ERROR;
        
    }

    public function delete(User $user, bool $flush = false)
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

    public function update( Object $data)
    {
            $role = $this->globals->em()->getRepository(Roles::class)->findOneBy(['rolesEmp' => $data->roleUser]);
            $user = $this->globals->em()->getRepository(User::class)->findOneBy(['email' => $data->email]);
            if ($role && $user) {
                $user->addRole($role);
                $user->setNom($data->nom)
                    ->setPrenom($data->prenom)
                    ->setActive(true)
                    ->setEmail($data->email)
                    ->setPassword($this->globals->hasher()->hashPassword($user, $data->password));
    
                //$this->globals->em()->persist($user);
                $this->globals->em()->flush();
                
                
    
                return $this->globals->success([
                    "User" => $user->getEmail()." Is update"
                ]);
            }

        return $this->globals->error(ManifestHttp::FROM_ERROR);
    }

    public function getById($id)
    {
        return $this->find($id) ? $this->find($id)->ListeUser() : null;
    }


}
