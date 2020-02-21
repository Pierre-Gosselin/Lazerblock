<?php

namespace App\Repository;

use DateTime;
use App\Entity\Booking;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    // /**
    //  * @return Booking[] Returns an array of Booking objects
    //  */
    
    public function findByReservationAt()
    {
        return $this->createQueryBuilder('b')
            ->groupBy('b.reservationAt')
            ->select('COUNT(DISTINCT b) as nbReservation')
            ->addSelect('b.reservationAt')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByBookingAt($bookingAt)
    {
        $date = DateTime::createFromFormat('d/m/Y', $bookingAt);

        return $this->createQueryBuilder('b')
            ->groupBy('b.timeSlot')
            ->where('b.reservationAt = :reservationAt')
            ->setParameter('reservationAt', $date->format('Y-m-d'))
            ->select('COUNT(DISTINCT b) as nbSlot')
            ->addSelect('b.timeSlot')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByDate($user)
    {
        $date = new \Datetime();
        $queryBuilder = $this->createQueryBuilder('b')
            ->andWhere('b.user = :user')
            ->setParameter('user', $user)
            ->andWhere('b.reservationAt > :reservationAt')
            ->setParameter('reservationAt', $date->format('Y-m-d'))
            ->select('COUNT(b.user)')
            ->getQuery();
        return $queryBuilder->getSingleScalarResult();
    }

    public function findByScore($side)
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.user', 'bu')
            ->andWhere('bu.side = :side')
            ->setParameter('side', $side)
            ->orderBy('b.score', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }
}
