<?php

namespace App\Repository;

use App\Entity\ReservationEntity;
use App\Entity\RoomEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;

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
    public function findReservationsByPage(int $page, string $sortColumn, string $asc): array
    {
        return $this->createQueryBuilder('res')
            ->orderBy('res.' . $sortColumn, $asc == 'asc' ? 'ASC' : 'DESC')
            ->setMaxResults(10)
            ->setFirstResult(($page * 10) - 10)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    public function getAmount()
    {
        return $this->createQueryBuilder('res')
            ->select('count(res.id)')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
    public function getReservationsMonthlyByDate()
    {
        $data = [];
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT MONTH(date_created) AS months, COUNT(*) AS amounts 
        FROM reservation_entity
        GROUP BY MONTH(date_created)";
        $resultSet = $conn->executeQuery($sql);
        $results = $resultSet->fetchAllAssociative();
        /**
         * https://stackoverflow.com/a/18467892/1496972
         */
        foreach ($results as $result) {
            $dateObj = DateTime::createFromFormat('!m', (string) $result['months']);
            $monthName = $dateObj->format('F');
            $data[$monthName] = $result['amounts'];
        }
        return ($data);
    }
    public function checkAnyRoomAvailability(\DateTime $dateFrom, \DateTime $dateTo, int $adults, int $children = 0)
    {
        $data = [];
        $conn = $this->getEntityManager()->getConnection();
        $rooms = $this->getEntityManager()->getRepository(RoomEntity::class)->getAllRoomsIds();
        foreach ($rooms as $id) {

            $sql = "
            SELECT room_entity.id
            FROM room_entity
            LEFT JOIN reservation_entity 
            ON room_entity.id = reservation_entity.room_entity_id
            AND reservation_entity.date_from < :dateTo
            AND reservation_entity.date_to > :dateFrom
            WHERE reservation_entity.room_entity_id IS NULL
        ";
            $resultSet = $conn->executeQuery($sql, [
                'id' => $id,
                'dateFrom' => $dateFrom->format('Y-m-d'),
                'dateTo' => $dateTo->format('Y-m-d')
            ]);
            $data = $resultSet->fetchAllAssociative();
        }
        if (isset ($data[0])) {
            return $data[0]['id'];
        } else {
            return [];
        }
    }
    public function getPastMonthsIncome(int $months = 12): array
    {
        $currentYear = date('Y');
        $currentMonth = date('n');
        $pastMonthsIncome = [];
        $startYear = $currentYear;
        $startMonth = $currentMonth - 11;
        if ($startMonth <= 0) {
            $startMonth += 12;
            $startYear--;
        }

        for ($i = 0; $i < 12; $i++) {
            $year = $startYear;
            $month = $startMonth + $i;
            if ($month > 12) {
                $month -= 12;
                $year++;
            }

    
            $income = $this->getMonthlyIncome($year, $month);
            $monthNameShort = date('M', mktime(0, 0, 0, $month, 1));
    
            $pastMonthsIncome[$monthNameShort] = $income;
        }
    
        return $pastMonthsIncome;
    }
    
    private function getMonthlyIncome(int $year, int $month): float
    {
        $conn = $this->getEntityManager()->getConnection();
    
        $sql = "
            SELECT SUM(reservation_entity.price) AS monthly_income
            FROM reservation_entity
            WHERE YEAR(reservation_entity.date_from) = :year
            AND MONTH(reservation_entity.date_from) = :month
        ";
    
        $resultSet = $conn->executeQuery($sql, [
            'year' => $year,
            'month' => $month,
        ]);
    
        $result = $resultSet->fetchAssociative();
    
        return (float) $result['monthly_income'];
    }
    
    public function getPeopleInReservationsMonthly(int $months = 12): array
    {
        $currentYear = date('Y');
        $currentMonth = date('n');
        $past12MonthsPeople = [];

        // Start from 12 months ago
        $startYear = $currentYear;
        $startMonth = $currentMonth - 11;
        if ($startMonth <= 0) {
            $startMonth += 12;
            $startYear--;
        }

        for ($i = 0; $i < 12; $i++) {
            $year = $startYear;
            $month = $startMonth + $i;
            if ($month > 12) {
                $month -= 12;
                $year++;
            }

            $conn = $this->getEntityManager()->getConnection();

            $sql = "
            SELECT SUM(res.adults + res.children) AS total_people
            FROM reservation_entity res
            WHERE YEAR(res.date_from) = :year
            AND MONTH(res.date_from) = :month
        ";

            $resultSet = $conn->executeQuery($sql, [
                'year' => $year,
                'month' => $month,
            ]);

            $result = $resultSet->fetchAssociative();
            $totalPeople = $result['total_people'] ?? 0;

            $monthNameShort = date('M', mktime(0, 0, 0, $month, 1));

            $past12MonthsPeople[$monthNameShort] = $totalPeople;
        }

        return $past12MonthsPeople;
    }

    public function getAverageReservationDurationMonthly(int $months = 12): array
    {
        $currentYear = date('Y');
        $currentMonth = date('n');
        $averageDuration = [];
    
        // Start from 12 months ago
        $startYear = $currentYear;
        $startMonth = $currentMonth - 11;
        if ($startMonth <= 0) {
            $startMonth += 12;
            $startYear--;
        }
    
        for ($i = 0; $i < $months; $i++) {
            $year = $startYear;
            $month = $startMonth + $i;
            if ($month > 12) {
                $month -= 12;
                $year++;
            }
    
            $conn = $this->getEntityManager()->getConnection();
    
            $sql = "
                SELECT AVG(DATEDIFF(res.date_to, res.date_from) + 1) AS average_duration
                FROM reservation_entity res
                WHERE YEAR(res.date_from) = :year
                AND MONTH(res.date_from) = :month
            ";
    
            $resultSet = $conn->executeQuery($sql, [
                'year' => $year,
                'month' => $month,
            ]);
            $result = $resultSet->fetchAssociative();
            $averageDuration[date('M', mktime(0, 0, 0, $month, 1))] = round($result['average_duration'] ?? 0, 2);
        }
        return $averageDuration;
    }    
}
