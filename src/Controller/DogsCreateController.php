<?php

namespace App\Controller;

use App\Entity\Dog;
use App\Entity\Photo;
use App\Service\FileUploader;
use App\Repository\PhotoRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DogsCreateController extends AbstractController
{
    /**
     * @Route("/dogs/create", name="dogs_create")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('dogs_create/index.html.twig', array(
            'categories' => $categoryRepository->findAll()));
    }

    
    /**
     * @Route("/dogs/create/add", name="dogs_add_create")
     */
    public function add(Request $request, CategoryRepository $categoryRepository, KernelInterface $kernel, EntityManagerInterface $em)
    {
        
        $formData = $request->request->get('form');
        $dog = new Dog();
        $user = $this->getUser();
        if ($formData['submit']) {
            $categories = $categoryRepository->findOneBy(['id'=>$formData['category']]);
            $dog->setName($formData['name']);
            $dog->setSexFr($formData['sex']);
            $dog->setAge($formData['age']);
            $dog->setCategory($categories);
            $dog->setDescriptionFr($formData['descriptionFr']);
            $dog->setDescriptionEn($formData['descriptionEn']);
            $imgFile = $request->files->get('file');
            $dog->setUser($user);
            //If a file image exist on form data
            if ($imgFile) {
                $fileUploader = new FileUploader($kernel);
                // Check si l'image est inferieur a 2mb, si false, retourne une erreur
                if (false == $imgFile->getSize() || false == $fileUploader->isLessThan2Mb($imgFile->getSize())) {
                    $this->addFlash('warning', "La taille de l'image doit être inférieur a 2mb.");
                } else {
                    // If an image has been set to the entity, we remove the older one to replace with the new one
                    if ($dog->getPhoto()) {
                        $fileUploader->remove($dog->getPhoto());
                    }
                    $imgFileName = $fileUploader->upload($imgFile);
                    if ($imgFileName !== false) {
                        $dog->setPhoto($imgFileName);
                    } else {
                        $this->addFlash('warning', "Une erreur est survenu lors de l'import de l'image.");
                    }
                }
            }

            $this->addFlash('success', "Le contenu '{$formData['name']} a été ajouté.'");
            $em->persist($dog);
            $em->flush();
            return $this->redirectToRoute('dogs_create');
        } else {
            $this->addFlash('danger', "Un problème lors de la soumission du formulaire a été détécté.");
            return $this->redirectToRoute('dogs_add_create');
        }
    }
}
