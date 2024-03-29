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
    public function getAllRoomsIds()
    {
        $result = $this->createQueryBuilder('r')
            ->select('r.id')
            ->getQuery()
            ->getResult();

        $ids = array_column($result, 'id');
        return $ids;
    }
    public function checkAnyRoomAvailability(\DateTime $dateFrom, \DateTime $dateTo, int $adults, int $children)
    {
        $qb = $this->createQueryBuilder('r')
            ->select('r.id')
            ->innerJoin('r.reservations', 'res')
            ->andWhere('')
            ->distinct()
            ->getQuery()
            ->getResult();

        return $qb;
    }
    public function setRoomAvailability(int $id, bool $isTaken, \DateTime $dateFrom, \DateTime $dateTo)
    {

    }
    public function findRoomsByCapacity(): array
    {
        $roomsByCapacity = [];

        // Fetch all rooms
        $rooms = $this->findAll();

        // Count rooms by capacity
        $i = 0;
        foreach ($rooms as $room) {
            $capacity = $room->getPersons();
            if (!isset ($roomsByCapacity[$capacity])) {
                $roomsByCapacity[$i] = 0;
            }
            $roomsByCapacity[$i] = $capacity;
            $i++;
         }
        return $roomsByCapacity;
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
