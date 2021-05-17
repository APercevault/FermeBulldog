<?php

namespace App\Controller;

use App\Entity\SitePage;
use App\Repository\SitePageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class ContentController extends AbstractController
{
    /**
     * @Route("/content", name="content")
     */
    public function index(SitePageRepository $sitePageRepository)
    {
        return $this->render('content/index.html.twig', array(
            'contents' => $sitePageRepository->findAll(),
            'pageTitle' => 'Gestion des pages'
        ));
    }

    /**
     * @Route("/content/{id}/edit", name="content_edit")
     */
    public function edit(Request $request, SitePage $sitePage, KernelInterface $kernel, EntityManagerInterface $em)
    {
        $formData = $request->request->get('form');

        if ($formData['submit']) {

            $sitePage->setTitleFr($formData['titleFr']);
            $sitePage->setTitleEn($formData['titleEn']);
            $sitePage->setTextEn($formData['textEn']);
            $sitePage->setTextfr($formData['textFr']);
            $imgFile = $request->files->get('file');

            //If a file image exist on form data
            if ($imgFile) {
                $fileUploader = new FileUploader($kernel);
                // Check si l'image est inferieur a 2mb, si false, retourne une erreur
                if (false == $imgFile->getSize() || false == $fileUploader->isLessThan2Mb($imgFile->getSize())) {
                    $this->addFlash('warning', "La taille de l'image doit être inférieur a 2mb.");
                } else {
                    // If an image has been set to the entity, we remove the older one to replace with the new one
                    if ($sitePage->getImgFile()) {
                        $fileUploader->remove($sitePage->getImgFile());
                    }
                    $imgFileName = $fileUploader->upload($imgFile);
                    if ($imgFileName !== false) {
                        $sitePage->setImgFile($imgFileName);
                    } else {
                        $this->addFlash('warning', "Une erreur est survenu lors de l'import de l'image.");
                    }
                }
            }

            $this->addFlash('success', "Le contenu '{$formData['titleFr']} a été modifié.'");
            $em->persist($sitePage);
            $em->flush();
            return $this->redirectToRoute('content', ['id' => $sitePage->getId()]);
        } else {
            $this->addFlash('danger', "Un problème lors de la soumission du formulaire a été détécté.");
            return $this->redirectToRoute('content');
        }
    }
}
