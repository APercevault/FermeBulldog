<?php

namespace App\Controller;

use App\Form\SearchAnnonceType;
use App\Repository\NewsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NewsController extends AbstractController
{
    /**
     * @Route("/news", name="News")
     */
    public function index(Request $request, NewsRepository $newsRepository): Response
    {

        $form = $this->createForm(SearchAnnonceType::class);
        $news = $newsRepository->findAll();
        $search = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // On recherche les annonces correspondant aux mots clés
            $news = $newsRepository->search(
                $search->get('words')->getData(),
                $search->get('Rechercher')->getData()
            );
        }

        return $this->render('News/index.html.twig', array(
            'news' => $news,
            'form' => $form->createView()
        ));
    }
    
    /**
     * @Route("{id}/newSingle", name="newSingle")
     */
    public function newSingle(int $id, NewsRepository $newRepository): Response
    {
        $new = $newRepository->findOneBy(['id' => $id]);

        
        return $this->render('news/newSingle.html.twig', array(
            'new' => $new
        ));
    }
}
