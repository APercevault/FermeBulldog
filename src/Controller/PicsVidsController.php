<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PicsVidsController extends AbstractController
{
    /**
     * @Route("/pics/vids", name="pics_vids")
     */
    public function index(): Response
    {
        return $this->render('pics_vids/index.html.twig', [
            'controller_name' => 'PicsVidsController',
        ]);
    }
}
