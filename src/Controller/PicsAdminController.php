<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Service\FileUploader;
use App\Repository\PhotoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PicsAdminController extends AbstractController
{
    /**
     * @Route("/pics/admin", name="pics_admin")
     */
    public function index(PhotoRepository $photoRepository): Response
    {
        $photos = $photoRepository->findAll();

        return $this->render('pics_admin/index.html.twig', array(
            'photos' => $photos,
        ));
    }

      /**
     * @Route("/addPhoto", name="addPhoto")
     */
    public function addCategory(KernelInterface $kernel, Request $request, EntityManagerInterface $em)
    {
        $photo = new Photo;
        $imgFile = $request->files->get('file');

        //If a file image exist on form data
        if ($imgFile) {
            $fileUploader = new FileUploader($kernel);
            // Check si l'image est inferieur a 2mb, si false, retourne une erreur
            if (false == $imgFile->getSize() || false == $fileUploader->isLessThan2Mb($imgFile->getSize())) {
                $this->addFlash('warning', "La taille de l'image doit être inférieur a 2mb.");
            } else {
                // If an image has been set to the entity, we remove the older one to replace with the new one
                if ($photo->getPhoto()) {
                    $fileUploader->remove($photo->getPhoto());
                }
                $imgFileName = $fileUploader->upload($imgFile);
                if ($imgFileName !== false) {
                    $photo->setPhoto($imgFileName);
                } else {
                    $this->addFlash('warning', "Une erreur est survenu lors de l'import de l'image.");
                }
            }
        }

        $em->persist($photo);
        $em->flush();
        return $this->redirectToRoute('pics_admin');
    }

     /**
     * @Route("/pics/{id}/delete", name="pics_delete")
     */
    public function delete(Photo $photo, EntityManagerInterface $em)
    {
        $em->remove($photo);
        $em->flush();
        return $this->redirectToRoute('pics_admin');
    }
}
