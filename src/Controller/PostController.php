<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/registrar-post", name="RegistrarPost")
     */
    public function index(Request $request, ManagerRegistry $doctrine)
    {
        //request viene del httpfundation 
        // importo POST
        $post = new Post();
        //recibe dos params postype y el segundo parametro el post nuevo
        $form = $this-> createForm(PostType::class, $post);
        //handleRequest detecta si el form fue enviado
        $form->handleRequest($request); 
        //si fue envida y es valido 
        if($form->isSubmitted() && $form->isValid())
        {
            //obtengo el user
            $user = $this->getUser();
            //edito el post
            $post->setUser($user);
            //emtitymangar guarda los datos en la db
            $em = $doctrine->getManager();
            //persisto la informas
            $em->persist($post);
            //confirmo los datos en la db 
            $em->flush();
            //retorno  la redireccion
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('post/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
