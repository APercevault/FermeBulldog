<?php

namespace App\Controller;

use App\Entity\News;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class NewsCreateController extends AbstractController
{
    /**
     * @Route("/news/create", name="news_create")
     */
    public function index(): Response
    { 
        
        return $this->render('news_create/index.html.twig');
    }

    /**
     * @Route("/news/create/add", name="news_add_create")
     */
    public function edit(Request $request, KernelInterface $kernel, EntityManagerInterface $em)
    {
        $formData = $request->request->get('form');
        $news = new News();
        $user = $this->getUser();
        if ($formData['submit']) {

            $news->setTitleFr($formData['titleFr']);
            $news->setTitleEn($formData['titleEn']);
            $news->setTextEn($formData['textEn']);
            $news->setTextfr($formData['textFr']);
            $news->setUser($user);
            $imgFile = $request->files->get('file');

            //If a file image exist on form data
            if ($imgFile) {
                $fileUploader = new FileUploader($kernel);
                // Check si l'image est inferieur a 2mb, si false, retourne une erreur
                if (false == $imgFile->getSize() || false == $fileUploader->isLessThan2Mb($imgFile->getSize())) {
                    $this->addFlash('warning', "La taille de l'image doit être inférieur a 2mb.");
                } else {
                    // If an image has been set to the entity, we remove the older one to replace with the new one
                    if ($news->getNewsImg()) {
                        $fileUploader->remove($news->getNewsImg());
                    }
                    $imgFileName = $fileUploader->upload($imgFile);
                    if ($imgFileName !== false) {
                        $news->setNewsImg($imgFileName);
                    } else {
                        $this->addFlash('warning', "Une erreur est survenu lors de l'import de l'image.");
                    }
                }
            }

            $this->addFlash('success', "Le contenu '{$formData['titleFr']} a été ajouté.'");
            $em->persist($news);
            $em->flush();
            return $this->redirectToRoute('news_edit');
        } else {
            $this->addFlash('danger', "Un problème lors de la soumission du formulaire a été détécté.");
            return $this->redirectToRoute('news_create');
        }
    }
}
