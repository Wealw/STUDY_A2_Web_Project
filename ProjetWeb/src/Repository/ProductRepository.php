<?php

namespace App\Repository;

use App\Entity\Merch\Product;
use App\Entity\Merch\ProductSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param ProductSearch $search
     * @return Query
     */
    public function findAllVisibleQuery(ProductSearch $search) : Query
    {
        $query = $this->findVisibleQuery();

        if($search->getMaxPrice())
        {
            $query = $query
                ->andWhere('p.productPrice <= :maxprice')
                ->setParameter('maxprice', $search->getMaxPrice());
        }
        if($search->getInStock())
        {
            $query = $query
                ->andWhere('p.productInventory > 0');
        }
        if($search->getType())
        {
            $query = $query
                ->andWhere('p.productType = :type')
                ->setParameter('type', $search->getType());
        }
        return $query->getQuery();
    }

    /**
     * @return QueryBuilder
     */
    public function findVisibleQuery() : QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->where('p.isOrderable = true');
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
