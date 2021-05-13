<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DogsController extends AbstractController
{
    /**
     * @Route("/dogs", name="dogs")
     */
    public function index(): Response
    {
        return $this->render('dogs/index.html.twig', [
            'controller_name' => 'DogsController',
        ]);
    }
}
