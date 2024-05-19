<?php

namespace App\Controller;

use App\Repository\OrderDetailsRepository;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(OrderDetailsRepository $or,OrderRepository $nb): Response
    {
        $nbOrders=$nb->getNBOrders();
        $ordre=$or->getOrders();
        //dd($ordre);
        return $this->render('dashboard/index.html.twig', [
            'orders' => $ordre,'nbOrders'=>$nbOrders
        ]);
    }
}
