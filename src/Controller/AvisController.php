<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article; 

#[Route('/avis')]
class AvisController extends AbstractController
{
    #[Route('/', name: 'app_avis_index', methods: ['GET'])]
    public function index(AvisRepository $avisRepository): Response
    {
        return $this->render('avis/index.html.twig', [
            'avis' => $avisRepository->findAll(),
        ]);
    }

    #[Route('/new/{articleId}', name: 'app_avis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, $articleId): Response
    {
        // Récupérer l'article en fonction de son identifiant
        $article = $entityManager->getRepository(Article::class)->find($articleId);

        if (!$article) {
            throw $this->createNotFoundException('Article not found for id '.$articleId);
        }

        $avi = new Avis();
        // Associer l'article à l'avis
        $avi->setArticle($article);

        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($avi);
            $entityManager->flush();

            return $this->redirectToRoute('app_avis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('avis/new.html.twig', [
            'avi' => $avi,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_avis_show', methods: ['GET'])]
    public function show(Avis $avi): Response
    {
        $article = $avi->getArticle(); // Obtenez l'article associé à cet avis
        return $this->render('avis/show.html.twig', [
            'avi' => $avi,
            'article' => $article, // Passez l'article au template
        ]);
    }
    

    #[Route('/{id}/edit', name: 'app_avis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Avis $avi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_avis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('avis/edit.html.twig', [
            'avi' => $avi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_avis_delete', methods: ['POST'])]
    public function delete(Request $request, Avis $avi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avi->getId(), $request->request->get('_token'))) {
            $entityManager->remove($avi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_avis_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route("/article/{id}/avis", name:"app_avis_article_show",methods: ['GET', 'POST'])]

    public function showByArticle($id, AvisRepository $avisRepository): Response
    {
        $articleAvis = $avisRepository->findBy(['article' => $id]);

        return $this->render('avis/show_by_article.html.twig', [
            'articleAvis' => $articleAvis,
        ]);
    }
}

