<?php

namespace App\Controller;

use App\Form\ChercherType;
use App\Repository\CategorieRepository;
use App\Repository\LivresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RechercherController extends AbstractController
{
    #[Route('/rechercher', name: 'app_rechercher')]
    public function search(Request $request, LivresRepository $livrep, CategorieRepository $catrep): Response
    {
        $categories = $catrep->findAll();
        $searchTerm = $request->query->get('search');

        if ($searchTerm) {
            $query = $livrep->createQueryBuilder('l')
                ->leftJoin('l.Categorie', 'c')
                ->where('l.titre LIKE :titre OR c.libelle LIKE :libelle or l.auteur LIKE :auteur ')
                ->setParameter('titre','%' . $searchTerm . '%' )
                ->setParameter('libelle', '%' . $searchTerm . '%')
                ->setParameter('auteur', '%' . $searchTerm . '%')
                ->getQuery();

            $livres = $query->getResult();
        } else {
            $livres = $livrep->findAll();
        }

        return $this->render('home/index.html.twig', [
            'livres' => $livres,
            'categories' => $categories,
        ]);
        }
}