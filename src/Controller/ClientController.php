<?php

namespace App\Controller;


use App\Entity\Client;
use App\Entity\ClientOffer;
use App\Entity\Order;
use App\Entity\OrderState;
use App\Entity\Specialist;
use App\Entity\SpecialistRating;
use App\Entity\SpecialistRespond;
use App\Entity\User;
use App\Form\OrderFormType;
use App\Form\RatingFormType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        $orderStateRepository = $this->entityManager->getRepository(OrderState::class);
        $client = $this->getCurrentClient();

        $inPendingStateId = $orderStateRepository->findOneBy(['stateName' => 'STATE_IN_PENDING'])->getId();
        $inProgressStateId = $orderStateRepository->findOneBy(['stateName' => 'STATE_IN_PROGRESS'])->getId();
        $archivedStateId = $orderStateRepository->findOneBy(['stateName' => 'STATE_ARCHIVE'])->getId();

        $notAppointedOrders = $this->orderRepository->findByNotAppointedSpecialists($inPendingStateId, $client->getId());

        $appointedOrders = $this->orderRepository->findByAppointedSpecialist($inProgressStateId, $client->getId());

        $archivedOrders = $this->orderRepository->findByArchivedOrders($archivedStateId, $client->getId());

        return $this->render('client/orders.html.twig', [
            'notAppointedOrders' => $notAppointedOrders,
            'appointedOrders' => $appointedOrders,
            'archivedOrders' => $archivedOrders,
        ]);
    }

    #[Route('/client/rating_to_specialist/{id}', name: 'app_client_leave_feedback')]
    public function leaveFeedbackForSpecialist(int $id, Request $request): Response
    {
        $feedback = new SpecialistRating();
        $form = $this->createForm(RatingFormType::class, $feedback);
        $form->handleRequest($request);

        $client = $this->getCurrentClient();

        $specialist = $this->entityManager->getRepository(Specialist::class)->find($id);

        if ($form->isSubmitted() && $form->isValid()) {

            $currentDate = new \DateTimeImmutable('now');

            $feedback->setRatingText($form->get('ratingText')->getData());
            $feedback->setRating($request->get('rate'));
            $feedback->setCreatedAt($currentDate);
            $feedback->setClient($client);
            $feedback->setSpecialist($specialist);

            $this->entityManager->persist($feedback);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_client_homepage');
        }

        return $this->render('client/create_rating_to_spec.html.twig', [
            'feedbackForm' => $form->createView(),
            'specialist' => $specialist,
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

            $orderState = $this->entityManager->getRepository(OrderState::class)->findOneBy(['stateName' => 'STATE_IN_PENDING']);

            $order->setOrderState($orderState);
            $order->setCreatedAt(new \DateTimeImmutable('now'));
            $order->setUpdatedAt(new \DateTimeImmutable('now'));

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

    #[Route('/client/orders/complete/{id}', name: 'app_client_complete_order', methods: ['GET'])]
    public function completeOrder(int $id): Response
    {
        $order = $this->orderRepository->find($id);

        $orderStateRepository = $this->entityManager->getRepository(OrderState::class);

        $archivedState = $orderStateRepository->findOneBy(['stateName' => 'STATE_ARCHIVE']);

        $order->setOrderState($archivedState);

        $this->entityManager->persist($order);
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
        $order = $this->orderRepository->find($id);

        $specialistRepository = $this->entityManager->getRepository(Specialist::class);

        $specialists = $specialistRepository->findAll();

        return $this->render('client/search_specialists.html.twig', [
            'specialists' => $specialists,
            'order' => $order,
        ]);
    }

    #[Route('/client/orders/{orderId}/specialists/{specId}', name: 'app_client_offer_order_to_specialist')]
    public function offerOrderToSpecialist(int $orderId, int $specId): Response
    {
        $client = $this->getCurrentClient();

        $specialistRepository = $this->entityManager->getRepository(Specialist::class);
        $specialist = $specialistRepository->find($specId);

        $order = $this->orderRepository->find($orderId);

        $offer = new ClientOffer();

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

        return $this->redirectToRoute('app_client_available_specialists', ['id' => $orderId]);
    }

    #[Route('/client/orders/{id}/related_specialists', name: 'app_client_related_specialists')]
    public function orderRelatedSpecialists(int $id, Request $request): Response
    {
        $order = $this->orderRepository->find($id);
        $client = $this->getCurrentClient();

        $offerRepository = $this->entityManager->getRepository(ClientOffer::class);
        $respondRepository = $this->entityManager->getRepository(SpecialistRespond::class);

        $relatedSpecialists = [];

        $relatedOffersByClient = $offerRepository->findBy(['order' => $order, 'client' => $client]);
        $relatedRespondsBySpec = $respondRepository->findBy(['order' => $order]);

        $relatedSpecialists = array_merge($relatedSpecialists, $relatedOffersByClient);

        foreach ($relatedRespondsBySpec as $respond) {
            foreach ($relatedOffersByClient as $offer) {
                if ($respond->getOrderId() !== $offer->getOrder()) {
                    $relatedSpecialists[] = $respond;
                }
            }
        }

        return $this->render('client/related_specialists.html.twig', [
            'order' => $order,
            'relatedSpecialists' => $relatedSpecialists,
        ]);
    }

    #[Route('/client/orders/{orderId}/related_specialists/{specId}', name: 'app_client_appoint_specialist')]
    public function appointSpecialistToOrder(int $orderId, int $specId): Response
    {
        $specialistRepository = $this->entityManager->getRepository(Specialist::class);

        $specialist = $specialistRepository->find($specId);

        $order = $this->orderRepository->find($orderId);

        $orderStateRepository = $this->entityManager->getRepository(OrderState::class);
        $inProgressState = $orderStateRepository->findOneBy(['stateName' => 'STATE_IN_PROGRESS']);

        $order->setSpecialist($specialist);
        $order->setOrderState($inProgressState);

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_client_homepage');
    }

    private function getCurrentClient(): Client
    {
        $currentUser = $this->security->getUser();

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $currentUser->getUserIdentifier()]);

        return $this->entityManager->getRepository(Client::class)->findOneBy(['user' => $user]);
    }
}
