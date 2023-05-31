<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    #[Route('/client/orders', name: 'app_client_homepage')]
    public function dashboard(): Response
    {
        return $this->render('client/orders.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }
}
