<?php

namespace App\Repository;

use App\Entity\Merch\CommandProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CommandProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandProduct[]    findAll()
 * @method CommandProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandProduct::class);
    }


    public function findMostSold()
    {
        /*
        SELECT product.product_name, product.product_price, product.product_image_path, product.product_description FROM product
LEFT JOIN command_product ON command_product.product_id = product.id
WHERE product_id IS NOT NULL AND product.is_orderable = true
GROUP BY product.id
ORDER BY quantity DESC
        */
        return $this->createQueryBuilder('cd')
            ->groupBy('cd.product')
            ->orderBy('cd.quantity', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return CommandProduct[] Returns an array of CommandProduct objects
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
    public function findOneBySomeField($value): ?CommandProduct
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
