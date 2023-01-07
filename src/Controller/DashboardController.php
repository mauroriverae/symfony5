<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// mangerregistry es el equivalente a entity manager,  puede guardar, remover, modifcar y consular la db
use Doctrine\Persistence\ManagerRegistry;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(ManagerRegistry $doctrine): Response
    {   
        // con solo esta linea traemos todos los registros de la db
        // findAll trae todo los post de la db
        $posts = $doctrine->getRepository(Post::class)->findAll();
        $post = $doctrine->getRepository(Post::class)->find(1);
        return $this->render('dashboard/index.html.twig', [
            'posts' => $posts,
            'post' => $post,
        ]);
    }
}
