<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Service\Slugger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\QueryBuilder;


#[Route('/article')]
class ArticleController extends AbstractController
{   
    
    #[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier que le titre de l'article n'est pas nul avant de générer le slug
            if ($article->getTitle() !== null) {
                $slug = $slugger->slug($article->getTitle())->lower(); // Convertir en minuscules
                $article->setSlug($slug); // Utiliser le slug obtenu
            } else {
                // Si le titre est null, attribuer un slug par défaut
                $article->setSlug('article-sans-titre');
            }
        
            $entityManager->persist($article);
            $entityManager->flush();
        
            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }
        
    
        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }
    


    #[Route('/{id}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }

    
    #[Route('/recent', name: 'app_article_recent', methods: ['GET'])]
    public function recentArticles(ArticleRepository $articleRepository): Response
    {
        // Récupérer les articles triés par date de création décroissante
        $articles = $articleRepository->findBy([], ['createdAt' => 'DESC']);
    
        return $this->render('article/recent.html.twig', [
            'articles' => $articles,
        ]);
    }

    
    
}
