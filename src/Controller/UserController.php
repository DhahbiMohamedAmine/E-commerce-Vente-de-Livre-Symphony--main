<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(UserRepository  $rep): Response
    {
        $users= $rep->findAll();
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/{id<\d+>}', name: 'app_user_show')]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/user/delete/{id}', name: 'app_user_delete')]
        public function delete(User $user,EntityManagerInterface $em): Response
        {
            $em->remove($user);
            $em->flush();
            return $this->redirectToRoute('app_user');
        }


        #[Route('/user/update/{id}', name: 'app_user_update')]
        public function update(User $user,EntityManagerInterface $em,Request $request): Response
            {
                
                #creation d'un objet formulaire
                $form=$this->createForm(UserType::class,$user);
                #recuperation et traitement des données
                $form->handleRequest($request);
                if($form->isSubmitted()){
                    $em->persist($user);
                    $em->flush();
                    return $this->redirectToRoute('app_user');
                }
                #affichage du formulaire
                return $this->render('user/update.html.twig', [
                    'f' => $form,
                ]);
            }
}
