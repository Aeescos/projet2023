<?php

namespace App\Repository;

use App\Entity\DeleteU;
use App\Entity\Roles;
use App\Utils\Globals;
use App\Utils\ManifestHttp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeleteURepository extends ServiceEntityRepository
{
    private  Globals $globals;
    
    
    public function __construct(ManagerRegistry $registry, Globals $globals)
    {
        parent::__construct($registry, DeleteU::class);
        $this->globals = $globals;
    }

    public function registerUser( Object $data)
    {

            $user   = (new DeleteU()) 
                    ->setNom($data->nom)
                    ->setPrenom($data->prenom)
                    ->setActive(true)
                    ->setEmail($data->email);
            $role = $this->globals->em()->getRepository(Roles::class)->findOneBy(['rolesEmp' => $data->roleUser]);

            if (!$role) {
                        // Créer une nouvelle instance de Roles si elle n'existe pas déjà
                $role = new Roles();
                $role->setRolesEmp($data->roleUser);
                $this->globals->em()->persist($role);
                $this->globals->em()->flush();
            }

            if ($role) {
                $user->addRole($role);
                $user->setPassword($this->globals->hasher()->hashPassword($user, $data->password));
    
                $this->globals->em()->persist($user);
                $this->globals->em()->flush();
                
                
    
                return $this->globals->success([
                    "User" => $user->ListeUser()
                ]);
            }

        return $this->globals->error(ManifestHttp::FROM_ERROR);
        
    }

    public function remove(DeleteU $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAll(array $criteria = [])
    {
        return $this->findAll($criteria);
    }

    public function update(DeleteU $client)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->flush();
    }

    public function delete(DeleteU $client)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($client);
        $entityManager->flush();
    }

    public function getById($id)
    {
        return $this->find($id) ? $this->find($id)->ListeUser() : null;
    }


}
