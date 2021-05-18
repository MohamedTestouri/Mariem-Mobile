<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }
    public function findById($id)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id = :id ')
            ->setParameter('id', $id)
       //     ->orderBy('r.id', 'ASC')

            ->getQuery()
            ->getResult()            ;
    }


    public function countPriceByDest()
    {
        return $this->createQueryBuilder('p')
           ->select('sum(p.prix) as totalPrice','p.destination as dest')
            ->groupBy('p.destination')
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Reservation[] Returns an array of Reservation objects
    //  */



//return $this->createQueryBuilder('p')
//->select('count(p.prix) as totalPrice','p.localisation as dest')
//->groupBy('p.localisation')
//->getQuery()
//->getResult();
    /*
    public function findOneBySomeField($value): ?Reservation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
