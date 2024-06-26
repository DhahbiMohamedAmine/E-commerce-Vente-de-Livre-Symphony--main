<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
class CategorieController extends AbstractController
{
    #[Route('/admin/categorie', name: 'admin_categorie')]
    public function index(CategorieRepository $rep): Response
    {
        $categories=$rep->findAll();
        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/categorie/create', name: 'admin_categorie_create')]
    public function create(EntityManagerInterface $em,Request $request): Response
    {
        $categorie=new Categorie();
        #creation d'un objet formulaire
        $form=$this->createForm(CategorieType::class,$categorie);
        #recuperation et traitement des données
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('admin_categorie');
        }
        #affichage du formulaire
        return $this->render('categorie/create.html.twig', [
            'f' => $form,
        ]);
    }#[IsGranted('ROLE_ADMIN')]
        #[Route('/admin/categorie/update/{id}', name: 'admin_categorie_update')]
        public function update(Categorie $categorie,EntityManagerInterface $em,Request $request): Response
        {
            #creation d'un objet formulaire
            $form=$this->createForm(CategorieType::class,$categorie);
            #recuperation et traitement des données
            $form->handleRequest($request);
            if($form->isSubmitted()){
                $em->persist($categorie);
                $em->flush();
                return $this->redirectToRoute('admin_categorie');
            }
            #affichage du formulaire
            return $this->render('categorie/update.html.twig', [
                'f' => $form,
            ]);
        }#[IsGranted('ROLE_ADMIN')]
        #[Route('/admin/categorie/delete/{id}', name: 'admin_categorie_delete')]
        public function delete(Categorie $categorie,EntityManagerInterface $em): Response
        {
            $em->remove($categorie);
            $em->flush();
            return $this->redirectToRoute('admin_categorie');
        }
        #[Route('/admin/categorie/{id<\d+>}', name: 'admin_categorie_show')]
        public function show(Categorie $categorie): Response
        {
            return $this->render('categorie/show.html.twig', [
                'categories' => $categorie,
            ]);
        }
    }