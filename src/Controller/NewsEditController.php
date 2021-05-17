<?php

namespace App\Controller;

use App\Entity\News;
use Symfony\Component\HttpFoundation\Request;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsEditController extends AbstractController
{
    /**
     * @Route("/news/edit", name="news_edit")
     */
    public function index(NewsRepository $newsRepository): Response
    {
        return $this->render('news_edit/index.html.twig', array(
            'news' => $newsRepository->findAll(),
            'pageTitle' => 'Gestion des news'
        ));
    }
 
    /**
     * @Route("/news/{id}/edit", name="news_id_edit")
     */
    public function edit(Request $request, News $news, KernelInterface $kernel, EntityManagerInterface $em)
    {
        $formData = $request->request->get('form');

        if ($formData['submit']) {

            $news->setTitleFr($formData['titleFr']);
            $news->setTitleEn($formData['titleEn']);
            $news->setTextEn($formData['textEn']);
            $news->setTextfr($formData['textFr']);
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

            $this->addFlash('success', "Le contenu '{$formData['titleFr']} a été modifié.'");
            $em->persist($news);
            $em->flush();
            return $this->redirectToRoute('news_edit', ['id' => $news->getId()]);
        } else {
            $this->addFlash('danger', "Un problème lors de la soumission du formulaire a été détécté.");
            return $this->redirectToRoute('news_edit');
        }
    }

    /**
     * @Route("/news/{id}/delete", name="news_id_delete")
     */
    public function delete(News $news, EntityManagerInterface $em)
    {
        $em->remove($news);
        $em->flush();
        return $this->redirectToRoute('news_edit');
    }
}
