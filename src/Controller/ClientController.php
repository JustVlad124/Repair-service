<?php

namespace App\Controller;


use App\Entity\Client;
use App\Entity\ClientOffer;
use App\Entity\Order;
use App\Entity\Specialist;
use App\Entity\SpecialistRespond;
use App\Entity\User;
use App\Form\OfferOrderToSpecialistFormType;
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


    #[Route('/client/profile', name: 'app_client_profile')]
    public function clientProfile(): Response
    {
        $client = $this->getCurrentClient();

        return $this->render('client/profile.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/client/orders', name: 'app_client_homepage')]
    public function orders(): Response
    {
        $client = $this->getCurrentClient();

        $notAppointedOrders = $this->orderRepository->findByNotAppointedSpecialists();

        $appointedOrders = $this->orderRepository->findByAppointedSpecialist();

        return $this->render('client/orders.html.twig', [
            'notAppointedOrders' => $notAppointedOrders,
            'appointedOrders' => $appointedOrders,
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

    #[Route('/client/orders/edit/{id}', name: 'app_client_update_order', methods: ['GET', 'POST'])]
    public function updateOrder(int $id, Request $request): Response
    {
        $order = $this->orderRepository->find($id);
        $form = $this->createForm(OrderFormType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        }

        return $this->render('client/update_order.html.twig', [
            'updateForm' => $form->createView(),
            'order' => $order,
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


    #[Route('/client/specialist_profile/{id}', name: 'app_client_specialist_profile')]
    public function showSpecialistProfile(int $id): Response
    {
        $specialistRepository = $this->entityManager->getRepository(Specialist::class);

        $specialist = $specialistRepository->find($id);

        return $this->render('client/specialist_profile.html.twig', [
            'specialist' => $specialist,
        ]);
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
    public function showAvailableSpecialists(int $id, Request $request): Response
    {
        $offer = new ClientOffer();

        $order = $this->orderRepository->find($id);

        $specialistRepository = $this->entityManager->getRepository(Specialist::class);

        $specialists = $specialistRepository->findAll();

        if ($request->getMethod() === Request::METHOD_POST) {

            $client = $this->getCurrentClient();
            $specialist = $specialistRepository->find($request->get('specialist_id'));

            $offer->setOrder($order);
            $offer->setClient($client);
            $offer->setSpecialist($specialist);

            $offerRepository = $this->entityManager->getRepository(ClientOffer::class);

            if (!$offerRepository->findBy(['client' => $client, 'order' => $order, 'specialist' => $specialist])) {
                $this->entityManager->persist($offer);
                $this->entityManager->flush();
            } else {
                print '<script>alert("Вы уже отправили заявку этому пользователю.")</script>';
            }
        }

        return $this->render('client/search_specialists.html.twig', [
            'specialists' => $specialists,
            'order' => $order,
        ]);
    }

    #[Route('/client/orders/{id}/related_specialists', name: 'app_client_related_specialists')]
    public function orderRelatedSpecialists(int $id, Request $request): Response
    {
        $order = $this->orderRepository->find($id);
        $client = $this->getCurrentClient();

        $offerRepository = $this->entityManager->getRepository(ClientOffer::class);
        $respondRepository = $this->entityManager->getRepository(SpecialistRespond::class);

        $relatedOffersByClient = $offerRepository->findBy(['order' => $order, 'client' => $client]);
        $relatedRespondsBySpec = $respondRepository->findBy(['order' => $order]);

        $relatedSpecialists = array_merge($relatedOffersByClient, $relatedRespondsBySpec);

        if ($request->getMethod() === Request::METHOD_POST) {
            $specialistRepository = $this->entityManager->getRepository(Specialist::class);

            $specialist = $specialistRepository->find($request->get('specialist_id'));

            $order->setSpecialist($specialist);

            $this->entityManager->persist($order);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_client_homepage');
        }

        return $this->render('client/related_specialists.html.twig', [
            'order' => $order,
            'relatedSpecialists' => $relatedSpecialists,
        ]);
    }

    private function getCurrentClient(): Client
    {
        $currentUser = $this->security->getUser();

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $currentUser->getUserIdentifier()]);

        return $this->entityManager->getRepository(Client::class)->findOneBy(['user' => $user]);
    }
}
