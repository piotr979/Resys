<?php

namespace App\Controller\Admin;

use App\Entity\CustomerEntity;
use App\Entity\ReservationEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $customersMonthlyData = $entityManager->getRepository(CustomerEntity::class)->getCustomersMonthlyByDate();
        $reservationsMonthlyData = $entityManager->getRepository(ReservationEntity::class)->getReservationsMonthlyByDate();
        return $this->render('admin/dashboard/dashboard.html.twig', [
            'customersMonthlyData' => $customersMonthlyData,
            'reservationsMonthlyData' => $reservationsMonthlyData,
        ]);
    }
}
