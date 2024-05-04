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
    public function rechercher(Request $request, LivresRepository $liv): Response
    {
        $rechercher=$this->createForm(ChercherType::class);
        $livres=[];
        if($rechercher->handleRequest($request)->isSubmitted()  && $rechercher->isValid()) {
            $test= $rechercher->getData();
            $livres = $liv->rechercher($test);
        }
        $rechercher->getErrors(true);
        return $this->render('rechercher/index.html.twig', [
            'chercher' => $rechercher->createView(),
            'livres' => $livres,
        ]);
    }
}
