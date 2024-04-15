<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjetdetailleController extends AbstractController
{
    #[Route('/projetdetaille', name: 'app_projetdetaille')]
    public function index(): Response
    {
        return $this->render('projetdetaille/index.html.twig', [
            'controller_name' => 'ProjetdetailleController',
        ]);
    }
}
