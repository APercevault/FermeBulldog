<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();

        // On crée le FormBuilder grâce au service form factory
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $user);

        // On ajoute les champs de l'entité que l'on veut à notre formulaire
        $formBuilder
            ->add('firstName',      TextType::class)
            ->add('lastName',      TextType::class)
            ->add('email',      TextType::class)
            ->add('password',   PasswordType::class)
            ->add('Valider',      SubmitType::class);

        // À partir du formBuilder, on génère le formulaire
        $form = $formBuilder->getForm();



        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $lastName = $form->get("lastName")->getData();
            $firstName = $form->get("firstName")->getData();
            $email = $form->get("email")->getData();
            $Password = $form->get("password")->getData();

            $user->setPassword($Password);
            $user->setEmail($email);
            $user->setLastName($lastName);
            $user->setFirstName($firstName);
            $user->setRoles(array('ROLE_ADMIN'));
            // Encode le mot de passe
            $Password = $passwordEncoder->encodePassword($user, $Password);
            $user->setPassword($Password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return
                $this->redirectToRoute('home');
        }
        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
