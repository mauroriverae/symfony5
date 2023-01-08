<?php

namespace App\Controller;

use App\Entity\Post;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     */
    public function index(PaginatorInterface $paginator, Request $request)
    {   
        // con solo esta linea traemos todos los registros de la db
        // findAll trae todo los post de la db
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Post::class)->buscarPost();
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            2 /*limit per page*/
        );

        return $this->render('dashboard/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
