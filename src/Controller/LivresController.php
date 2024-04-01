<?php

namespace App\Controller;

use App\Entity\Livres;
use App\Repository\LivresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
    #[Route('/admin/livres/create', name: 'app_admin_livres_create')]
    public function create(EntityManagerInterface $em): Response
    {
        $livre=new Livres();
        $livre->setAuteur('Auteur4')
              ->setDateEdition(new \DateTime('13-03-2022'))
              ->setEditeur('Eni')
              ->setImage('https://picsum.photos/536/354')
              ->setISBN('122.3333.3222.1113')
              ->setPrix(200)
              ->setResume('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iure commodi itaque aspernatur fugit neque porro ipsa error esse consequatur? Omnis sit repellat natus, odit facilis quia esse consectetur ipsa quasi!')
              ->setSlug('titre-4')
              ->setTitre('titre4');
              $em->persist($livre);
              $em->flush();
              dd($livre);
    }
    #[Route('/admin/livres/delete/{id}', name: 'app_admin_livres_delete')]
    public function delete(EntityManagerInterface $em,Livres $livre):Response
    {

        $em->remove($livre);
        $em->flush();
        dd($livre);
    }
    #[Route('/admin/livres/update/{id}', name: 'app_admin_livres_update')]
    public function update(EntityManagerInterface $em ,Livres $livre): Response
    {
        $livre->setPrix(1000);
        $em->persist($livre);
        $em->flush();
        dd($livre);
    }
}

