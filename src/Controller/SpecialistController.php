<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Specialist;
use App\Entity\SpecialistRespond;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpecialistController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager,
                                private Security $security)
    {
    }

    #[Route('/specialist', name: 'app_specialist_homepage')]
    public function index(): Response
    {
        return $this->render('specialist/index.html.twig', [
            'controller_name' => 'SpecialistController',
        ]);
    }

    #[Route('/specialist/available_orders', name: 'app_specialist_available_orders')]
    public function showAllAvailableOrders(Request $request): Response
    {
        $orderRepository = $this->entityManager->getRepository(Order::class);

        $orders = $orderRepository->findAll();

        if ($request->getMethod() === Request::METHOD_POST) {
            $respond = new SpecialistRespond();

            $order = $orderRepository->find($request->get('order_id'));
            $specialist = $this->getCurrentSpecialist();

            $respond->setOrderId($order);
            $respond->setSpecialist($specialist);

            $this->entityManager->persist($respond);
            $this->entityManager->flush();
        }

        return $this->render('specialist/available_orders.html.twig', [
            'orders' => $orders,
        ]);
    }

    private function getCurrentSpecialist(): Specialist
    {
        $currentUser = $this->security->getUser();

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $currentUser->getUserIdentifier()]);

        return $this->entityManager->getRepository(Specialist::class)->findOneBy(['user' => $user]);
    }
}
