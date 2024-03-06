<?php

namespace App\Controller\Admin;

use App\Entity\RoomEntity;
use App\Form\RoomFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/room')]
class RoomController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }
    #[Route('/rooms-list/{page}/{sortColumn}/{orderBy}', name: 'rooms_list', 
    defaults: [
        'page' => '1',
        'sortColumn' => 'id',
        'orderBy' => 'asc'])]
    public function list(int $page, string $sortColumn, string $orderBy): Response
    {
        $amountOfRooms = $this->entityManager->getRepository(RoomEntity::class)->getAmount();
        $rooms = $this->entityManager->getRepository(RoomEntity::class)->findRoomsByPage($page, $sortColumn, $orderBy);
        return $this->render('admin/rooms/rooms-list.html.twig', [
            'rooms' => $rooms,
            'pagesAmount' => (ceil($amountOfRooms / 10)),
            'currentPage' => $page,
            'sortColumn' => $sortColumn,
            'currentColumn' => $sortColumn,
            'orderBy' => $orderBy,
        ]);
    }
    #[Route('/new-room', name: 'room_new')]
    public function newRoom(Request $request): Response
    {
        $room = new RoomEntity();
        $form = $this->createForm(RoomFormType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $room = $form->getData();
            $this->entityManager->persist($room);
            $this->entityManager->flush();
            return $this->redirectToRoute('rooms_list');
        }
        return $this->render('admin/rooms/new-room.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/edit-room/{id}/{currentPage}', name: 'room_edit', defaults: ['currentPage' => 1])]
    public function updateRoom(int $id, int $currentPage, Request $request): Response
    {
        $room = $this->entityManager->getRepository(RoomEntity::class)->find($id);

        if (!$room) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }
        $form = $this->createForm(RoomFormType::class, $room, ['currentPage' => $currentPage]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $room = $form->getData();
            $this->entityManager->persist($room);
            $this->entityManager->flush();
            $this->addFlash('notice', 'Changes have been saved.');
            return $this->redirectToRoute('rooms_list', ['page' => $currentPage]);
        }
        return $this->render('admin/rooms/edit-room.html.twig', [
            'form' => $form,
            'currentPage' => $currentPage,
        ]);

    }
    #[Route('/remove/{id}', name: 'room_delete')]
    public function removeRoom(int $id): Response
    {
        $room = $this->entityManager->getRepository(RoomEntity::class)->find($id);
        if (!$room) {
            throw $this->createNotFoundException('No room found for id ' . $id);
        }
        $this->entityManager->remove($room);
        $this->entityManager->flush();
        $this->addFlash('notice', 'Room has been removed.');
        return $this->redirectToRoute('rooms_list');
    }
}
