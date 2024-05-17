<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Repository\LivresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commandes', name: 'app_orders_')]
class CommandeController extends AbstractController
{
    #[Route('/ajout', name: 'add')]
    public function add(SessionInterface $session, LivresRepository $livreRepository, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $panier = $session->get('panier', []);

        if($panier === []){
            $this->addFlash('message', 'Votre panier est vide');
            return $this->redirectToRoute('panier_index');
        }

        
        $order = new Order;
        $order->setUserId($this->getUser());
        $order->setReference(uniqid());
        foreach($panier as $item => $quantity){
            $orderDetails = new OrderDetails();
            $product = $livreRepository->find($item);
            
            $price = $product->getPrix();
            $orderDetails->setLivres($product);
            $orderDetails->setPrice($price);
            $orderDetails->setQuantity($quantity);

            $order->addOrdersDetail($orderDetails);
        }
        $em->persist($order);
        $em->flush();

        $session->remove('panier');

        $this->addFlash('message', 'Commande créée avec succès');
        return $this->redirectToRoute('panier_index');
    }
}