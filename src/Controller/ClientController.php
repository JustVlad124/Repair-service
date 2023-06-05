<?php

namespace App\Controller;


use App\Entity\Client;
use App\Entity\Order;
use App\Entity\Specialist;
use App\Entity\User;
use App\Form\OrderFormType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;


class ClientController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager,
                                private OrderRepository $orderRepository,
                                private Security $security)
    {
    }

    #[Route('/client/orders', name: 'app_client_homepage')]
    public function orders(): Response
    {
        $client = $this->getCurrentClient();

        $orders = $this->orderRepository->findBy(['client' => $client]);

        return $this->render('client/orders.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/client/orders/create', name: 'app_client_create_order')]
    public function createOrder(Request $request): Response
    {
        $order = new Order();
        $form = $this->createForm(OrderFormType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order->setOrderName($form->get('orderName')->getData());
            $order->setDescription($form->get('description')->getData());
            $order->setCost($form->get('cost')->getData());

            $client = $this->getCurrentClient();

            $order->setClient($client);

            $this->entityManager->persist($order);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_client_homepage');
        }

        return $this->render('client/create_order.html.twig', [
            'createOrderForm' => $form->createView(),
        ]);
    }

    #[Route('/client/orders/delete/{id}', name: 'app_client_delete_order', methods: ['GET', 'DELETE'])]
    public function deleteOrder(int $id): Response
    {
        $order = $this->orderRepository->find($id);

        $this->entityManager->remove($order);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_client_homepage');
    }

    #[Route('/client/orders/{id}', name: 'app_client_show_order', methods: ['GET'])]
    public function showOrder(int $id): Response
    {
        $order = $this->orderRepository->find($id);

        return $this->render('client/show_order.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/client/orders/{id}/specialists', name: 'app_client_available_specialists')]
    public function showAvailableSpecialists(int $id): Response
    {
        $order = $this->orderRepository->find($id);

        $specialistRepository = $this->entityManager->getRepository(Specialist::class);

        $specialists = $specialistRepository->findAll();

        return $this->render('client/search_specialists.html.twig', [
            'specialists' => $specialists,
            'order' => $order,
        ]);
    }

    private function getCurrentClient(): Client
    {
        $currentUser = $this->security->getUser();

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $currentUser->getUserIdentifier()]);

        return $this->entityManager->getRepository(Client::class)->findOneBy(['user' => $user]);
    }
}
