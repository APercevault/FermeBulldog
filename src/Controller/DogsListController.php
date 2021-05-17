<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Dog;
use App\Service\FileUploader;
use App\Repository\DogRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DogsListController extends AbstractController
{
    /**
     * @Route("/dogs/list", name="dogs_list")
     */
    public function index(DogRepository $dogRepository, CategoryRepository $categoryRepository): Response
    {

        $dogs = $dogRepository->findAll();

        return $this->render('dogs_list/index.html.twig', array(
            'categories' => $categoryRepository->findAll(),
            'dogs' => $dogs,
        ));
    }

    /**
     * @Route("/dogs/{id}/edit", name="dogs_id_edit")
     */
    public function edit(Request $request, CategoryRepository $categoryRepository, Dog $dog, KernelInterface $kernel, EntityManagerInterface $em)
    {
        $formData = $request->request->get('form');
        $categories = $categoryRepository->findOneBy(['id' => $formData['category']]);

        if ($formData['submit']) {

            $dog->setName($formData['name']);
            $dog->setSexFr($formData['sex']);
            $dog->setCategory($categories);
            $dog->setAge($formData['age']);
            $dog->setDescriptionFr($formData['descriptionfr']);
            // $dog->setDescriptionEn($formData['textFr']);
            $imgFile = $request->files->get('file');

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

            $this->addFlash('success', "Le contenu '{$formData['name']} a été modifié.'");
            $em->persist($dog);
            $em->flush();
            return $this->redirectToRoute('dogs_list', ['id' => $dog->getId()]);
        } else {
            $this->addFlash('danger', "Un problème lors de la soumission du formulaire a été détécté.");
            return $this->redirectToRoute('dogs_list');
        }
    }

    /**
     * @Route("/news/{id}/delete", name="dog_id_delete")
     */
    public function delete(Dog $dog, EntityManagerInterface $em)
    {
        $em->remove($dog);
        $em->flush();
        return $this->redirectToRoute('dogs_list');
    }

    /**
     * @Route("/addCategory", name="addCategory")
     */
    public function addCategory(Request $request, EntityManagerInterface $em)
    {
        $category = new Category;
        $formData = $request->request->get('form');
        if ($formData['submit']) {
            $category->setNameCategoryFr($formData['category']);
        }
        $em->persist($category);
        $em->flush();
        return $this->redirectToRoute('dogs_list');
    }
}
