<?php

namespace App\Repository;

use App\Entity\Evenements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Evenements|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenements|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenements[]    findAll()
 * @method Evenements[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementsRepository extends ServiceEntityRepository
{

    public function filterEvents($localisation)
    {
        return $this -> createQueryBuilder('e')
            ->andWhere('e.localisation = :loc')
            ->setParameter('loc', $localisation)
            ->orderBy('e.nombre_place', 'DESC')
            ->getQuery()
            ->getResult();
    }



    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenements::class);
    }

    // /**
    //  * @return Evenements[] Returns an array of Evenements objects
    //  */



public function findByCategory($value){
        return $this -> createQueryBuilder('e')
            ->andWhere('e.categorie = :category')
            ->setParameter('category', $value)
            ->getQuery()
            ->getResult();
}
public function orderByPrice(){
        return $this -> createQueryBuilder('e')
            ->orderBy('e.prix', 'ASC')
            ->getQuery()
            ->getResult();
}
    public function orderByPlace(){
        return $this -> createQueryBuilder('e')
            ->orderBy('e.nombrePlace', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Evenements
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
