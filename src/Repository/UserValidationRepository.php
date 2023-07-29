<?php

namespace App\Repository;


use App\Entity\UserValidation;
use App\Utils\Globals;
use App\Utils\ManifestHttp;
use App\Utils\UTILS;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserValidation>
 *
 * @method UserValidation|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserValidation|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserValidation[]    findAll()
 * @method UserValidation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserValidationRepository extends ServiceEntityRepository
{
    private  Globals $globals;
    
    
    public function __construct(ManagerRegistry $registry, Globals $globals)
    {
        parent::__construct($registry, UserValidation::class);
        $this->globals = $globals;
    }

    public function registerUser(string $dTE, $email, string $code)
    {
        $dE = substr($dTE, 0, strpos($dTE, ' '));
        $tE = substr($dTE,strpos($dTE, ' '), strlen($dTE));
        $user = (new UserValidation()) 
                ->setDExpire($dE)
                ->setTExpire($tE)
                ->setEmail($email)
                ->setCodeValidation($code);
        $isUser = $this->globals->em()->getRepository(UserValidation::class)->findOneBy(['email' => $email]);

        if (!$isUser) {
            $this->globals->em()->persist($user);
            $this->globals->em()->flush();
            return UTILS::ADD;
        }

        return UTILS::ERROR;
        
    }


    public function getAll(array $criteria = [])
    {
        return $this->findAll($criteria);
    }

    public function update(string $dTE, string $id, string $email, string $code)
    {
        $dE = substr($dTE, 0, strpos($dTE, ' '));
        $tE = substr($dTE, strpos($dTE, ' '), strlen($dTE));
        $entityManager = $this->globals->em();
        $user = $entityManager->getRepository(UserValidation::class)->find($id);
    
        if ($user) {
            $user->setDExpire($dE)
                ->setTExpire($tE)
                ->setEmail($email)
                ->setCodeValidation($code);
            $entityManager->flush();
            return UTILS::ADD;
        }
    
        return UTILS::ERROR;
    }

    public function delete(UserValidation $user, bool $flush = false)
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

    public function getById($id)
    {
        return $this->find($id) ? $this->find($id)->ListeUser() : null;
    }

}
