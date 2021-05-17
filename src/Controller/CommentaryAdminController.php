<?php

namespace App\Controller;

use App\Entity\Commentary;
use App\Repository\CommentaryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentaryAdminController extends AbstractController
{
    /**
     * @Route("/commentary/admin", name="commentary_admin")
     */
    public function index(CommentaryRepository $commentaryRepository): Response
    {

        $commentaries = $commentaryRepository->findAll();


        return $this->render('commentary_admin/index.html.twig', array(
            'commentaries' => $commentaries,
        ));
    }

    /**
     * @Route("/commentary/admin/{id}/valid", name="commentary_admin_valid")
     */
    public function valid(Commentary $commentary, Request $request, EntityManagerInterface $em): Response
    {

        $formData = $request->request->get('form');

        if ($formData['submit']) {

            $commentary->setValid($formData['valid']);

            $em->persist($commentary);
            $em->flush();
            return $this->redirectToRoute('commentary_admin');
        } else {
            $this->addFlash('danger', "Un problème lors de la soumission du formulaire a été détécté.");
            return $this->redirectToRoute('commentary_admin');
        }
    }
}
