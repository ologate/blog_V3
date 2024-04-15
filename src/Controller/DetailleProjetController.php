<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DetailleProjetController extends AbstractController
{
    #[Route('/detaille/projet', name: 'app_detaille_projet')]
    public function index(): Response
    {
        return $this->render('detaille_projet/index.html.twig', [
            'controller_name' => 'DetailleProjetController',
        ]);
    }
}
