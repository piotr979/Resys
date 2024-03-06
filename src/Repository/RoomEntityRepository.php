<?php

namespace App\Repository;

use App\Entity\RoomEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RoomEntity>
 *
 * @method RoomEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoomEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoomEntity[]    findAll()
 * @method RoomEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomEntity::class);
    }
    public function findRoomsByPage(int $page, string $sortColumn, string $asc): array
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.' . $sortColumn, $asc == 'asc' ? 'ASC' : 'DESC')
            ->setMaxResults(10)
            ->setFirstResult(($page * 10) - 10)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    public function getAmount()
    {
        return $this->createQueryBuilder('r')
            ->select('count(r.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
    //    /**
    //     * @return RoomEntity[] Returns an array of RoomEntity objects
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

    //    public function findOneBySomeField($value): ?RoomEntity
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
