<?php

namespace App\Repository;

use App\Entity\Social\Event;
use App\Entity\Social\EventSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @return Query
     */
    public function findRequestVisible(EventSearch $search): Query
    {
        $query = $this->createQueryBuilder('e')
            ->andWhere('e.event_is_visible = 1')
            ->orderBy('e.event_created_at', 'DESC');

        if ($search->getMaxPrice()) {
            $query = $query
                ->andWhere('e.event_price <= :maxPrice')
                ->setParameter('maxPrice', $search->getMaxPrice());
        }

        return $query->getQuery();
    }

    /**
     * @return mixed
     */
    public function findLatestVisible()
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.event_is_visible = 1')
            ->orderBy('e.event_created_at', 'DESC')
            ->setMaxResults(12)
            ->getQuery()
            ->getResult();
    }

    public function findNextVisible()
    {
        $now = (new \DateTime())->format('Y-m-d 00:00:00');
        return $this->createQueryBuilder('e')
        ->andWhere('e.event_is_visible = 1')
        ->andWhere("e.event_date > '$now' ")
        ->orderBy('e.event_date', 'ASC')
        ->setMaxResults(12)
        ->getQuery()
        ->getResult();
    }

    public function findBetween(\DateTimeInterface $start, \DateTimeInterface $end)
    {
        return $this->createQueryBuilder('e')
            ->andWhere("e.event_date BETWEEN '{$start->format('Y-m-d 00:00:00')}' AND '{$end->format('Y-m-d 23:59:59')}'")
            ->orderBy('e.event_date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
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
