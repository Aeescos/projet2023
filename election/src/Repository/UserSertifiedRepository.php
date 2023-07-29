<?php

namespace App\Repository;

use App\Entity\Roles;
use App\Entity\UserSertified;
use App\Utils\Globals;
use App\Utils\ManifestHttp;
use App\Utils\UTILS;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserSertified>
 *
 * @method UserSertified|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserSertified|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserSertified[]    findAll()
 * @method UserSertified[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserSertifiedRepository extends ServiceEntityRepository
{
    private  Globals $globals;
    
    
    public function __construct(ManagerRegistry $registry, Globals $globals)
    {
        parent::__construct($registry, UserSertified::class);
        $this->globals = $globals;
    }

    public function registerUser( UserSertified $user)
    {
            $userEmil = $user->getEmail();
            $userSert = $this->globals->em()->getRepository(UserSertified::class)->findOneBy(['email' => $user->getEmail()]);

            if (!$userSert) {
                $this->globals->em()->persist($user);
                $this->globals->em()->flush();
                return UTILS::USER_SERTIFIED;
            }

            return UTILS::ERROR;
        
    }

    public function remove(UserSertified $entity, bool $flush = false): void
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

    public function update(UserSertified $client)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->flush();
    }

    public function delete(UserSertified $client)
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
