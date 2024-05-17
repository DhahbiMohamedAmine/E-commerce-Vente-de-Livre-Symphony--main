<?php

namespace App\Controller;

use App\Entity\Livres;
use App\Repository\LivresRepository;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/panier', name: 'panier_')]
class PanierController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, LivresRepository $livresRepository)
    {
        $panier = $session->get('panier', []);
        $data = [];
        $total = 0;

        foreach($panier as $id => $qte){
            $livre = $livresRepository->find($id);

            $data[] = [
                'livre' => $livre,
                'qte' => $qte
            ];
            $total += $livre->getPrix() * $qte;
        }
        
        return $this->render('panier/index.html.twig', compact('data', 'total'));
    }


    #[Route('/add/{id}', name: 'add')]
    public function add(Livres $livre, SessionInterface $session)
    {
        $id = $livre->getId();
        $panier = $session->get('panier', []);
        
        if(empty($panier[$id])) {
            $panier[$id] = 1;
            $session->set('panier', $panier);
            return $this->redirectToRoute('app_home');
        } else {
            $panier[$id]++;
            $session->set('panier', $panier);
            return $this->redirectToRoute('panier_index');
        }
    }
    
    #[Route('/remove/{id}', name: 'remove')]
    public function remove(Livres $livre, SessionInterface $session)
    {
        $id = $livre->getId();
        $panier = $session->get('panier', []);
        if(!empty($panier[$id])){
            if($panier[$id] > 1){
                $panier[$id]--;
            }else{
                unset($panier[$id]);
            }
        }
        $session->set('panier', $panier);
        return $this->redirectToRoute('panier_index');
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Livres $livre, SessionInterface $session)
    {
        $id = $livre->getId();
        $panier = $session->get('panier', []);

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }
        $session->set('panier', $panier);
        return $this->redirectToRoute('panier_index');
    }

    #[Route('/empty', name: 'empty')]
    public function empty(SessionInterface $session)
    {
        $session->remove('panier');
        return $this->redirectToRoute('panier_index');
    }

    #[Route('/cart/checkout/{totals}', name: 'app_cart_checkout')]
    public function checkout($totals,SessionInterface $session): Response
    {
        $session->remove('panier');

        return $this->checkouts($totals);
    }

    #[Route('/cart/success', name: 'app_cart_success')]
    public function success(): Response
    {
        // Handle payment success
        return $this->render('panier/success.html.twig');
    }

    #[Route('/cart/cancel', name: 'app_cart_cancel')]
    public function cancel(): Response
    {
        // Handle payment cancellation
        return $this->render('panier/cancel.html.twig');
    }

    public function checkouts( $totals): Response
    {
        Stripe::setApiKey('sk_test_51PG3uWClavGSdaZ6PGMVLxRE3Qs6JeZwOPJHNuGCc4hJdlkDhEiidWhYgevJBklDInVbJhAvW9S1L8zYMFnSPmya00AYam2yby');
        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'TTD',
                    'product_data' => [
                        'name' => 'livres',
                    ],
                    'unit_amount' => $totals*100 ,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' =>$this->generateUrl('panier_app_cart_success',[],UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('panier_app_cart_cancel',[],UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        // Return the checkout session ID
        return $this->redirect($checkout_session->url,303);
    }
}