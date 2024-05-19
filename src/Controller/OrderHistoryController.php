<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetails;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/commandes/historique', name: 'app_orders_history_')]
class OrderHistoryController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();
        $orders = $em->getRepository(Order::class)->findBy(['user_id' => $user]);

        $orderDetails = [];
        foreach ($orders as $order) {
            $orderDetails[$order->getId()] = $em->getRepository(OrderDetails::class)->findBy(['order' => $order]);
        }

        return $this->render('order_history/index.html.twig', [
            'orders' => $orders,
            'orderDetails' => $orderDetails
        ]);
    }
    }
