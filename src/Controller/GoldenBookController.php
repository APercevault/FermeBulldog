<?php

namespace App\Controller;

use App\Entity\Commentary;
use App\Service\FileUploader;
use App\Repository\CommentaryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GoldenBookController extends AbstractController
{
    /**
     * @Route("/golden/book", name="golden_book")
     */
    public function index(CommentaryRepository $commentaryRepository): Response
    {
        $commentaries = $commentaryRepository->findAll();

        return $this->render('golden_book/index.html.twig', array(
            'commentaries' => $commentaries,
        ));
    }


    /**
     * @Route("/golden/book/add", name="golden_book_add")
     */
    public function edit(Request $request, KernelInterface $kernel, EntityManagerInterface $em)
    {
        $formData = $request->request->get('form');
        $commentary = new Commentary;
        if ($formData['submit']) {
            $commentary->setFirstName($formData['firstName']);
            $commentary->setLastName($formData['lastName']);
            $commentary->setCommentary($formData['userCommentary']);
            $commentary->setRate($formData['rate']);
            $commentary->setValid(0);
            $imgFile = $request->files->get('file');
            //If a file image exist on form data
            if ($imgFile) {
                $fileUploader = new FileUploader($kernel);
                // Check si l'image est inferieur a 2mb, si false, retourne une erreur
                if (false == $imgFile->getSize() || false == $fileUploader->isLessThan2Mb($imgFile->getSize())) {
                    $this->addFlash('warning', "La taille de l'image doit être inférieur a 2mb.");
                } else {
                    // If an image has been set to the entity, we remove the older one to replace with the new one
                    if ($commentary->getCommentaryPhoto()) {
                        $fileUploader->remove($commentary->getCommentaryPhoto());
                    }
                    $imgFileName = $fileUploader->upload($imgFile);
                    if ($imgFileName !== false) {
                        $commentary->setCommentaryPhoto($imgFileName);
                    } else {
                        $this->addFlash('warning', "Une erreur est survenu lors de l'import de l'image.");
                    }
                }
            }
            $this->addFlash('success', "Le contenu '{$formData['firstName']} a été ajouté.'");
            $em->persist($commentary);
            $em->flush();
            return $this->redirectToRoute('golden_book');
        } else {
            $this->addFlash('danger', "Un problème lors de la soumission du formulaire a été détécté.");
            return $this->redirectToRoute('golden_book');
        }
    }

    
}
