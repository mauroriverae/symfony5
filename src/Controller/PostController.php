<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/registrar-post", name="RegistrarPost")
     */
    public function index(Request $request, ManagerRegistry $doctrine,  SluggerInterface $slugger)
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
            //obtengo el archivo 
            $brochureFile = $form->get('foto')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                // prevee que no se sobrescriban si hay una con el mismo nombre y ataques
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new Exception('ocurrio un error');
                }
                $post->setFoto($newFilename);
            }
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
        return $this->render('post/index.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/post/{id}", name="verPost") 
     */
    public function verPost($id){
        $em = $this->getDoctrine()->getManager();
        $post = $em ->getRepository(Post::class)->find($id);
        return $this->render('post/verPost.html.twig', ['post'=> $post]);
    }

    /**
     * @Route("/mispost", name="MisPost") 
     */
    public function misPost(){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $posts = $em->getRepository(Post::class)->findBy(['user'=>$user]);
        return $this->render('post/misposts.html.twig', ['posts'=> $posts]);

    }
}
