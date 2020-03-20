<?php

namespace App\Repository;

use App\Entity\Affectations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Affectations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Affectations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Affectations[]    findAll()
 * @method Affectations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AffectationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Affectations::class);
    }

   /**
     * @return Affectations[] Returns an array of Affectations objects
    */
    public function findCompteByUser($value)
    {
        $date = new \DateTime("now");
        return $this->createQueryBuilder('a')
            ->andWhere('a.user = :val')
            ->andWhere('a.affecterEndAt > :date')
            ->andWhere('a.affecterStartAt < :date')
            ->setParameter('val', $value)
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Affectations[] Returns an array of Affectations objects
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
    public function findOneBySomeField($value): ?Affectations
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
