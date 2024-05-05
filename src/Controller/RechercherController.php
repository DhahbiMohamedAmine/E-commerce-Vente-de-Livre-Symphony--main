<?php

namespace App\Controller;

use App\Form\ChercherType;
use App\Repository\LivresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RechercherController extends AbstractController
{
    #[Route('/rechercher', name: 'app_rechercher')]
    public function rechercher(Request $request, LivresRepository $livresRepository): Response
    {
        $chercherForm = $this->createForm(ChercherType::class);
        $livres = [];

        if ($chercherForm->handleRequest($request)->isSubmitted() && $chercherForm->isValid()) {
            $searchData = $chercherForm->getData();
            $livres = $livresRepository->search($searchData);
        }

        return $this->render('rechercher/index.html.twig', [
            'chercher' => $chercherForm->createView(),
            'livres' => $livres,
        ]);
    }
}