<?php

namespace App\Repository;

use App\Entity\AvisLocation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AvisLocation|null find($id, $lockMode = null, $lockVersion = null)
 * @method AvisLocation|null findOneBy(array $criteria, array $orderBy = null)
 * @method AvisLocation[]    findAll()
 * @method AvisLocation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvisLocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AvisLocation::class);
    }

    // /**
    //  * @return AvisLocation[] Returns an array of AvisLocation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AvisLocation
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
