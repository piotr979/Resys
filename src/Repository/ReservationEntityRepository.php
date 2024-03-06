<?php

namespace App\Repository;

use App\Entity\ReservationEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReservationEntity>
 *
 * @method ReservationEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservationEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservationEntity[]    findAll()
 * @method ReservationEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservationEntity::class);
    }

    //    /**
    //     * @return ReservationEntity[] Returns an array of ReservationEntity objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ReservationEntity
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
