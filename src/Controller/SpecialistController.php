<?php

namespace App\Controller;

use App\Entity\ClientOffer;
use App\Entity\Order;
use App\Entity\OrderState;
use App\Entity\Portfolio;
use App\Entity\Specialist;
use App\Entity\SpecialistRespond;
use App\Entity\User;
use App\Form\PortfolioFormType;
use App\Repository\SpecialistRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpecialistController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager,
                                private SpecialistRepository $specialistRepository,
                                private Security $security)
    {
    }

    #[Route('/specialist', name: 'app_specialist_homepage')]
    public function index(): Response
    {
        $orderRepository = $this->entityManager->getRepository(Order::class);

        $specialist = $this->getCurrentSpecialist();

        $inProgressState = $this->entityManager->getRepository(OrderState::class)->findBy(['stateName' => 'STATE_IN_PROGRESS']);
        $archiveState = $this->entityManager->getRepository(OrderState::class)->findBy(['stateName' => 'STATE_ARCHIVE']);

        $clientOffers = $this->entityManager->getRepository(ClientOffer::class)->findBy(['specialist' => $specialist]);
        $specialistResponds = $this->entityManager->getRepository(SpecialistRespond::class)->findBy(['specialist' => $specialist]);

        $inPendingOrders = [];

        foreach ($clientOffers as $offer) {
            if ($offer->getOrder()->getOrderState()->getStateName() === 'STATE_IN_PENDING') {
                $inPendingOrders[] = $offer->getOrder();
            }
        }

        foreach ($specialistResponds as $respond) {
            if ($respond->getOrderId()->getOrderState()->getStateName() === 'STATE_IN_PENDING'
                && !in_array($respond->getOrderId(), $inPendingOrders)) {
                $inPendingOrders[] = $respond->getOrderId();
            }
        }

        $inProgressOrders = $orderRepository->findBy([
            'specialist' => $specialist,
            'orderState' => $inProgressState,
        ]);

        $archiveOrders = $orderRepository->findBy([
            'specialist' => $specialist,
            'orderState' => $archiveState,
        ]);

        return $this->render('specialist/index.html.twig', [
            'controller_name' => 'SpecialistController',
            'inPendingOrders' => $inPendingOrders,
            'inProgressOrders' => $inProgressOrders,
            'archiveOrders' => $archiveOrders,
        ]);
    }

    #[Route('/specialist/available_orders', name: 'app_specialist_available_orders')]
    public function showAllAvailableOrders(Request $request): Response
    {
        $orderRepository = $this->entityManager->getRepository(Order::class);

        // Создаю уловие для выборки данных из БД
        // Запрашиваю все записи, у которых orderState не STATE_ARCHIVE
        $notAppointedOrders = $orderRepository->findByNotAppointedSpecialists(1);

//        $specificOrders = array_merge($notAppointedOrders, $appointedOrders);

//      добавить заказы, которые подходят специалистам, по категориям оказываемых усулу и по локации

        return $this->render('specialist/available_orders.html.twig', [
            'orders' => $notAppointedOrders,
        ]);
    }

    #[Route('/specialist/respond_to_order/{id}', name: 'app_specialist_respond')]
    public function respondToOrder(int $id): Response
    {
        $respond = new SpecialistRespond();

        $specialist = $this->getCurrentSpecialist();

        $order = $this->entityManager->getRepository(Order::class)->find($id);

        $respond->setOrderId($order);
        $respond->setSpecialist($specialist);

        $respondRepository = $this->entityManager->getRepository(SpecialistRespond::class);

        if (!$respondRepository->findBy(['order' => $order, 'specialist' => $specialist])) {
            $this->entityManager->persist($respond);
            $this->entityManager->flush();
        } else {
            return new Response('Вы уже откликались на этот заказ');
        }

        return $this->redirectToRoute('app_specialist_available_orders');
    }

    #[Route('//specialist/profile', name: 'app_specialist_profile')]
    public function specialistProfile(): Response
    {
        $specialist = $this->getCurrentSpecialist();

        return $this->render('specialist/profile.html.twig', [
            'specialist' => $specialist,
        ]);
    }

    #[Route('/specialist/add_profile', name: 'app_specialist_add_profile')]
    public function AddProfile(Request $request): Response
    {
        $portfolio = new Portfolio();
        $form = $this->createForm(PortfolioFormType::class, $portfolio);
        $form->handleRequest($request);

        $specialist = $this->getCurrentSpecialist();

        if ($form->isSubmitted() && $form->isValid()) {
            $imagePath = $form->get('imagePath')->getData();

            if ($imagePath) {
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                try {
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                $portfolio->setSpecialist($specialist);
                $portfolio->setImagePath('/uploads/' . $newFileName);
            }

            $this->entityManager->persist($portfolio);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_specialist_profile');
        }

        return $this->render('specialist/add_portfolio.html.twig', [
            'specialist' => $specialist,
            'portfolioForm' => $form->createView(),
        ]);
    }

    #[Route('/specialist/order/{id}', name: 'app_specialist_show_order')]
    public function showOrderDetails(int $id): Response
    {
        $orderRepository = $this->entityManager->getRepository(Order::class);

        $order = $orderRepository->find($id);

        return $this->render('specialist/order_details.html.twig', [
            'order' => $order,
        ]);
    }

    private function getCurrentSpecialist(): Specialist
    {
        $currentUser = $this->security->getUser();

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $currentUser->getUserIdentifier()]);

        return $this->specialistRepository->findOneBy(['user' => $user]);
    }
}
