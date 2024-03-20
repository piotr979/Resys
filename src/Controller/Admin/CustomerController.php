<?php

namespace App\Controller\Admin;

use App\Entity\CustomerEntity;
use App\Form\CustomerFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/customers')]
class CustomerController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }
    #[Route('/customers-list/{page}/{sortColumn}/{orderBy}', name: 'customers_list',
     defaults: [
        'page' => '1',
        'sortColumn' => 'id',
        'orderBy' => 'asc'])]
    public function list(int $page, string $sortColumn, string $orderBy): Response
    {
        $amountOfCustomers = $this->entityManager->getRepository(CustomerEntity::class)->getAmount();
        $customers = $this->entityManager->getRepository(CustomerEntity::class)->findCustomersByPage($page, $sortColumn, $orderBy);
        return $this->render('admin/customers/customers-list.html.twig', [
            'customers' => $customers,
            'pagesAmount' => (ceil($amountOfCustomers / 10)),
            'currentPage' => $page,
            'sortColumn' => $sortColumn,
            'currentColumn' => $sortColumn,
            'orderBy' => $orderBy,
        ]);
    }
    #[Route('/new-customer', name: 'customer_new')]
    public function newCustomer(Request $request): Response
    {
        $customer = new CustomerEntity();
        $form = $this->createForm(CustomerFormType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $customer = $form->getData();
            $customer->setDateCreated(new \DateTime('now'));
            $this->entityManager->persist($customer);
            $this->entityManager->flush();
            return $this->redirectToRoute('customers_list');
        }
        return $this->render('admin/customers/new-customer.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/edit-customer/{id}/{currentPage}', name: 'customer_edit', defaults: ['currentPage' => 1])]
    public function updateCustomer(int $id, int $currentPage, Request $request): Response
    {
        $customer = $this->entityManager->getRepository(CustomerEntity::class)->find($id);

        if (!$customer) {
            throw $this->createNotFoundException(
                'No customer found for id ' . $id
            );
        }
        $form = $this->createForm(CustomerFormType::class, $customer, ['currentPage' => $currentPage]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $customer = $form->getData();
            $this->entityManager->persist($customer);
            $this->entityManager->flush();
            $this->addFlash('notice', 'Changes have been saved.');
            return $this->redirectToRoute('customers_list', ['page' => $currentPage]);
        }
        return $this->render('admin/customers/edit-customer.html.twig', [
            'form' => $form,
            'currentPage' => $currentPage,
        ]);

    }
    #[Route('/remove/{id}', name: 'customer_delete')]
    public function removeCustomer(int $id): Response
    {
        $customer = $this->entityManager->getRepository(CustomerEntity::class)->find($id);
        if (!$customer) {
            throw $this->createNotFoundException('No customer found for id ' . $id);
        }
        $this->entityManager->remove($customer);
        $this->entityManager->flush();
        $this->addFlash('notice', 'Customer has been removed.');
        return $this->redirectToRoute('customers_list');
    }
    #[Route('customer-details/{id}/{currentPage}', name: 'customer_details',
        defaults: [
            'currentPage' => 1,
        ])]
    public function customerDetails(int $id, int $currentPage): Response
    {
        $details = $this->entityManager->getRepository(CustomerEntity::class)->find($id);
        return $this->render('admin/customers/customer-details.html.twig', [
            'details' => $details,
            'currentPage' => $currentPage,
        ]);
    }
}
