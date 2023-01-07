<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistroController extends AbstractController
{
    /**
     * @Route("/registro", name="app_registro")
     */
    public function index(Request $request, ManagerRegistry $doctrine, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user );
        $form -> handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            //obtengo pass user
            $user -> setPassword($passwordEncoder->encodePassword($user, $form['password']->getdata())); 
            $em->persist($user);
            $em->flush();
            $this-> addFlash('exito', User::REGISTR0_EXITOSO);
            return $this->redirectToRoute('app_registro');
        }
        return $this->render('registro/index.html.twig', [
            'controller_name' => 'RegistroController',
            'mivariable' => 'hola mundo',
            'formulario' => $form->createView()
        ]);
    }
}
