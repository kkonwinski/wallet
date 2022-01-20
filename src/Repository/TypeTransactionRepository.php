<?php

namespace App\Repository;

use App\Entity\TypeTransaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeTransaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeTransaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeTransaction[]    findAll()
 * @method TypeTransaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeTransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeTransaction::class);
    }

    // /**
    //  * @return TypeTransaction[] Returns an array of TypeTransaction objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeTransaction
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
