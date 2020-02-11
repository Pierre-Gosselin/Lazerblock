<?php

namespace App\Repository;

use App\Entity\Card;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Card|null find($id, $lockMode = null, $lockVersion = null)
 * @method Card|null findOneBy(array $criteria, array $orderBy = null)
 * @method Card[]    findAll()
 * @method Card[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Card::class);
    }

    public function findByDate()
    {
        $now = new \Datetime();

        return $this->createQueryBuilder('b')
            ->where("date_format(b.expireCreditsAt, '%Y-%m-%d') < :date")
            ->setParameter('date', $now->format('Y-m-d'))
            ->getQuery()
            ->getResult()
        ;
    }

    public function findExpireTwoWeeks()
    {
        $twoWeeks = new \Datetime('+2 week');

        return $this->createQueryBuilder('b')
            ->where("date_format(b.expireCreditsAt, '%Y-%m-%d') = :date")
            ->setParameter('date', $twoWeeks->format('Y-m-d'))
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Card[] Returns an array of Card objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Card
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
