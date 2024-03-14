<?php

namespace App\Controller\Admin;

use App\Entity\ReservationEntity;
use App\Form\ReservationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/reservations')]
class ReservationController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }
    #[Route('/reservations-list/{page}/{sortColumn}/{orderBy}', name: 'reservations_list',
     defaults: [
        'page' => '1',
        'sortColumn' => 'id',
        'orderBy' => 'asc'])]
    public function list(int $page, string $sortColumn, string $orderBy): Response
    {
        $amountOfReservations = $this->entityManager->getRepository(ReservationEntity::class)->getAmount();
        $reservations = $this->entityManager->getRepository(ReservationEntity::class)->findReservationsByPage($page, $sortColumn, $orderBy);
        return $this->render('admin/reservations/reservations-list.html.twig', [
            'reservations' => $reservations,
            'pagesAmount' => (ceil($amountOfReservations / 10)),
            'currentPage' => $page,
            'sortColumn' => $sortColumn,
            'currentColumn' => $sortColumn,
            'orderBy' => $orderBy,
        ]);
    }
    #[Route('/new-reservation', name: 'reservation_new')]
    public function newReservation(Request $request): Response
    {
        $reservation = new ReservationEntity();
        $form = $this->createForm(ReservationFormType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation = $form->getData();
            dump($reservation);die();
            $this->entityManager->persist($reservation);
            $this->entityManager->flush();
            return $this->redirectToRoute('reservations_list');
        }
        return $this->render('admin/reservations/new-reservation.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/edit-reservation/{id}/{currentPage}', name: 'reservation_edit', defaults: ['currentPage' => 1])]
    public function updateReservation(int $id, int $currentPage, Request $request): Response
    {
        $reservation = $this->entityManager->getRepository(ReservationEntity::class)->find($id);

        if (!$reservation) {
            throw $this->createNotFoundException(
                'No reservation found for id ' . $id
            );
        }
        $form = $this->createForm(ReservationFormType::class, $reservation, ['currentPage' => $currentPage]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reservation = $form->getData();
            $this->entityManager->persist($reservation);
            $this->entityManager->flush();
            $this->addFlash('notice', 'Changes have been saved.');
            return $this->redirectToRoute('reservations_list', ['page' => $currentPage]);
        }
        return $this->render('admin/reservations/edit-reservation.html.twig', [
            'form' => $form,
            'currentPage' => $currentPage,
        ]);

    }
    #[Route('/remove/{id}', name: 'reservation_delete')]
    public function removeReservation(int $id): Response
    {
        $reservation = $this->entityManager->getRepository(ReservationEntity::class)->find($id);
        if (!$reservation) {
            throw $this->createNotFoundException('No reservation found for id ' . $id);
        }
        $this->entityManager->remove($reservation);
        $this->entityManager->flush();
        $this->addFlash('notice', 'Reservation has been removed.');
        return $this->redirectToRoute('reservations_list');
    }
}
