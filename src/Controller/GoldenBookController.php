<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GoldenBookController extends AbstractController
{
    /**
     * @Route("/golden/book", name="golden_book")
     */
    public function index(): Response
    {
        return $this->render('golden_book/index.html.twig', [
            'controller_name' => 'GoldenBookController',
        ]);
    }
}
