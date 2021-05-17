<?php

namespace App\Controller;

use App\Repository\PhotoRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PicsController extends AbstractController
{
    /**
     * @Route("/pics", name="pics")
     */
    public function index(PhotoRepository $photoRepository): Response
    {
        $photos = $photoRepository->findAll();

        return $this->render('pics/index.html.twig', array(
            'photos' => $photos,
        ));}
}
