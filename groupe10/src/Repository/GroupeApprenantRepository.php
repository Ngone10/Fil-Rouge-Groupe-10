<?php

namespace App\Repository;

use App\Entity\GroupeApprenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GroupeApprenant|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupeApprenant|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupeApprenant[]    findAll()
 * @method GroupeApprenant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupeApprenantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupeApprenant::class);
    }

    // /**
    //  * @return GroupeApprenant[] Returns an array of GroupeApprenant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GroupeApprenant
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
