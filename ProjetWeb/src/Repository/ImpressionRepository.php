<?php

namespace App\Repository;

use App\Entity\Social\Impression;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Impression|null find($id, $lockMode = null, $lockVersion = null)
 * @method Impression|null findOneBy(array $criteria, array $orderBy = null)
 * @method Impression[]    findAll()
 * @method Impression[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImpressionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Impression::class);
    }

    public function findLike($userId)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.impression_user_id = :userId')
            ->setParameter('userId', $userId)
            ->andWhere("i.impression_type = :like")
            ->setParameter('like', 'like')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    public function findDislike($userId)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.impression_user_id = :userId')
            ->setParameter('userId', $userId)
            ->andWhere("i.impression_type = :dislike")
            ->setParameter('dislike', 'dislike')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }


    // /**
    //  * @return Impression[] Returns an array of Impression objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Impression
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
