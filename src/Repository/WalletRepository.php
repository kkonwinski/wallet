<?php

namespace App\Repository;

use App\Entity\Wallet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Wallet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wallet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wallet[]    findAll()
 * @method Wallet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WalletRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wallet::class);
    }

     /**
      * @return Wallet[] Returns an array of Wallet transactions
      */
    public function findWalletTransactions($value): array
    {
        return $this->createQueryBuilder('w')
            ->select('w.balance', 't.amount', 't.amountBefore', 't.createdAt', 'tt.name')
            ->leftJoin('w.transactions', 't')
            ->leftJoin('t.typeTransaction', 'tt')
            ->andWhere('w.id=:val')
            ->setParameter('val', $value)
            ->orderBy('t.createdAt', 'DESC')
//            ->setMaxResults(10)
            ->getQuery()
            ->getArrayResult();
    }


    /*
    public function findOneBySomeField($value): ?Wallet
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
