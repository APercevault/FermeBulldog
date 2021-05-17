<?php

namespace App\Controller;

use App\Entity\Dog;
use App\Repository\DogRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DogsController extends AbstractController
{
    /**
     * @Route("/dogs", name="dogs")
     */
    public function index(DogRepository $dogRepository, CategoryRepository $categoryRepository): Response
    {
        return $this->render('dogs/index.html.twig', array(
            'dogs' => $dogRepository->findAll(),
            'categories' => $categoryRepository->findAll()
        ));
    }
    /**
     * @Route("/findByCategory", name="findByCategory")
     */
    public function findByCategory(Request $request, DogRepository $dogRepository, CategoryRepository $categoryRepository): Response
    {

        $formData = $request->request->get('form');

        if ($formData['submit']==1) {
            $dogs = $dogRepository->findBy(['id' => $formData['category']]);
        } else {
            $dogs = $dogRepository->findAll();
        }

        return $this->render('dogs/index.html.twig', array(
            'categories' => $categoryRepository->findAll(),
            'dogs' => $dogs,
        ));
    }
    /**
     * @Route("{id}/dogSingle", name="dogSingle")
     */
    public function DogSingle(int $id, DogRepository $dogRepository, CategoryRepository $categoryRepository): Response
    {
        $dog = $dogRepository->findOneBy(['id' => $id]);


        return $this->render('dogs/dogSingle.html.twig', array(
            'dog' => $dog,
            'categories' => $categoryRepository->findAll()
        ));
    }
}
