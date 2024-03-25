<?php

namespace App\Controller\Admin;

use App\Entity\CustomerEntity;
use App\Entity\ReservationEntity;
use App\Entity\RoomEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(EntityManagerInterface $entityManager): Response
    {

        $customerEntity = $entityManager->getRepository(CustomerEntity::class);
        $reservationEntity = $entityManager->getRepository(ReservationEntity::class);
        $roomsEntity =  $entityManager->getRepository(RoomEntity::class);
        $reservationsMonthlyData = $reservationEntity->getReservationsMonthlyByDate();
        $customersMonthlyData = $customerEntity->getCustomersMonthlyByDate();
        return $this->render('admin/dashboard/dashboard.html.twig', [
            'customersMonthlyData' => $customersMonthlyData,
            'reservationsMonthlyData' => $reservationsMonthlyData,
            'lastMonthsIncomeData' =>  $reservationEntity->getPastMonthsIncome(),
            'peopleMonthlyData' => $reservationEntity->getPeopleInReservationsMonthly(12),
            'roomsByCapacityData' => $roomsEntity->findRoomsByCapacity(),
            'regularCustomers' => $customerEntity->getMostRegularCustomerList(),
            'averageMonthlyStayData' => $reservationEntity->getAverageReservationDurationMonthly(),
        ]);
    }
}
