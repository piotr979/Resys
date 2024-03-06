<?php

namespace App\Repository;

use App\Entity\CustomerEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CustomerEntity>
 *
 * @method CustomerEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerEntity[]    findAll()
 * @method CustomerEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerEntity::class);
    }
    public function findCustomersByPage(int $page, string $sortColumn, string $asc): array
    {
        return $this->createQueryBuilder('c')
                ->orderBy('c.' . $sortColumn, $asc == 'asc' ? 'ASC' : 'DESC')
                ->setMaxResults(10)
                ->setFirstResult(($page * 10) -10 )
                ->setMaxResults(10)
                ->getQuery()
                ->getResult()
                ;
    }
    public function getAmount() {
        return $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    //    /**
    //     * @return CustomerEntity[] Returns an array of CustomerEntity objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CustomerEntity
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
