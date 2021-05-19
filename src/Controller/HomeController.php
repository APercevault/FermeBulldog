<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\SitePageRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(SitePageRepository $sitePageRepository): Response
    {
        return $this->render('home/index.html.twig',array(
            'contents' => $sitePageRepository->findAll()));
    }
}
