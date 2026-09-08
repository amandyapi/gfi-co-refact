<?php

namespace App\Modules\Shared\Repository;

use App\Modules\Shared\Entity\File;
use App\Modules\Identity\Entity\Organization;
use App\Modules\Transaction\Entity\Topup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use App\Modules\Shared\Helpers\TypeHelper;

/**
 * @extends ServiceEntityRepository<File>
 *
 * @method File|null find($id, $lockMode = null, $lockVersion = null)
 * @method File|null findOneBy(array $criteria, array $orderBy = null)
 * @method File[]    findAll()
 * @method File[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, File::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(File $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(File $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findFile(Organization|null $organization, Topup|null $topup)
    {
        $query = $this->createQueryBuilder('f');

        if (isset($organization) && TypeHelper::is_not_null($organization) && $organization instanceOf Organization) {
                $query = $query
                    ->join('f.organization', 'o')
                    ->andWhere('o.code = :code')
                    ->setParameter('code', $organization->getCode());
        }

        if (isset($topup) && TypeHelper::is_not_null($topup) && $topup instanceOf Topup) {
                $query = $query
                    ->join('f.topup', 't')
                    ->andWhere('t.code = :code')
                    ->setParameter('code', $organization->getCode());
        }

        $query = $query
                    ->orderBy('f.createdAt', 'DESC')
                    ->setMaxResults(1)
                ;
        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return File[] Returns an array of File objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?File
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
