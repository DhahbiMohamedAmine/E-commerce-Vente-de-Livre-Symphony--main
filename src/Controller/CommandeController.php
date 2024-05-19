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

        if ($panier === []) {
            $this->addFlash('message', 'Votre panier est vide');
            return $this->redirectToRoute('panier_index');
        }

        $totals = 0; 

        $order = new Order;
        $order->setUserId($this->getUser());
        $order->setReference(uniqid());
        foreach ($panier as $item => $quantity) {
            $orderDetails = new OrderDetails();
            $product = $livreRepository->find($item);

            if ($product) {
                $price = $product->getPrix();
            
                if ($product->getQte() >= $quantity) {
                    $totals += $price * $quantity; 
                    $orderDetails->setLivres($product);
                    $orderDetails->setPrice($price);
                    $orderDetails->setQuantity($quantity);
                    $product->setQte($product->getQte() - $quantity);

                    $order->addOrdersDetail($orderDetails);
                } else {
                    $this->addFlash('error', 'La quantité de ' . $product->getTitre() . ' est insuffisante.');
                    return $this->redirectToRoute('panier_index');
                }
            }
        }
        $em->persist($order);
        $em->flush();

        $session->remove('panier');

        $this->addFlash('message', 'Commande créée avec succès');
        
        // Redirect to checkout route with total as parameter
        return $this->redirectToRoute('panier_app_cart_checkout', ['totals' => $totals]);
    }

}
