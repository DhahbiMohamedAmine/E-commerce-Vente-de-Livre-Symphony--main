<?php

namespace App\Controller;

use App\Entity\Livres;
use App\Form\LivreType;
use App\Repository\LivresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class LivresController extends AbstractController
{
    #[Route('/admin/livres', name: 'app_admin_livres')]

    public function index(LivresRepository $rep): Response
    {
        $livres = $rep->findAll();
        return $this->render('livres/index.html.twig', [
            'livres' => $livres,
        ]);
    }
    #[Route('/admin/livres/{id<\d+>}', name: 'app_admin_livres_show')]
    public function show(Livres $livre): Response
    {
        return $this->render('livres/show.html.twig', [
            'livre' => $livre,
        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/livres/create', name: 'app_admin_livres_create')]
    public function create(EntityManagerInterface $em,Request $request): Response
    {
        
        $livre=new Livres();
        #creation d'un objet formulaire
        $form=$this->createForm(LivreType::class,$livre);
        #recuperation et traitement des données
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em->persist($livre);
            $em->flush();
            return $this->redirectToRoute('app_admin_livres');
        }
        #affichage du formulaire
        return $this->render('livres/add.html.twig', [
            'f' => $form,
        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/livres/delete/{id}', name: 'app_admin_livres_delete')]
    public function delete(EntityManagerInterface $em,Livres $livre):Response
    {

        $em->remove($livre);
        $em->flush();
        dd($livre);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/livres/update/{id}', name: 'app_admin_livres_update')]
    public function update(Livres $livre,EntityManagerInterface $em,Request $request): Response
        {
            
            #creation d'un objet formulaire
            $form=$this->createForm(LivreType::class,$livre);
            #recuperation et traitement des données
            $form->handleRequest($request);
            if($form->isSubmitted()){
                $em->persist($livre);
                $em->flush();
                return $this->redirectToRoute('app_admin_livres');
            }
            #affichage du formulaire
            return $this->render('livres/update.html.twig', [
                'f' => $form,
            ]);
        }
    
}

