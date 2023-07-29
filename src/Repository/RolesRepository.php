<?php

namespace App\Repository;

use App\Entity\Roles;
use App\Utils\Globals;
use App\Utils\ManifestHttp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

/**
 * @extends ServiceEntityRepository<Roles>
 *
 * @method Roles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Roles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Roles[]    findAll()
 * @method Roles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RolesRepository extends ServiceEntityRepository
{
    private  Globals $globals;
    public function __construct(ManagerRegistry $registry, Globals $globals)
    {
        parent::__construct($registry, Roles::class);
        $this->globals = $globals;
    }

    public function save(String $data): Response
    {
        $roleUser = $this->globals->em()->getRepository(Roles::class)->findOneBy(['rolesEmp' => $data]);
        if (!$roleUser) {
            //Créer une nouvelle instance de Roles si elle n'existe pas
            $role = new Roles();
            $role->setRolesEmp(strtoupper($data));
            $this->globals->em()->persist($role);
            $this->globals->em()->flush();
            return $this->globals->SUCCESS(['message' => 'Roles add successfully']);
        }

        return $this->globals->error(ManifestHttp::ERROR);
    }

    public function delete(Roles $role, bool $flush = false)
    {
        $this->globals->em()->remove($role);

        if ($flush) {
            $this->globals->em()->flush();
            return $this->globals->success([
                "ROles " => $role->getRolesEmp()." Is deleted"
            ]);
        }
        return $this->globals->error(ManifestHttp::FROM_ERROR);
    }

    public function update( String $data)
    {
            $role = $this->globals->em()->getRepository(Roles::class)->findOneBy(['rolesEmp' => $data]);

            if ($role) {
                //update instance de Roles si elle n'existe pas
                $role = new Roles();
                $role->setRolesEmp($data);
                $this->globals->em()->flush();
                return $this->globals->SUCCESS(['message' => 'Roles update successfully']);
            }

        return $this->globals->error(ManifestHttp::FROM_ERROR);
    }

    public function getAll(array $criteria = [])
    {
        return $this->findAll($criteria);
    }

}
