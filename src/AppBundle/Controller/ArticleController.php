<?php
/**
 * Created by IntelliJ IDEA.
 * User: marc
 * Date: 05/10/2017
 * Time: 17:08
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends Controller
{
    /**
     * @Route("/article/{id}", name="show_article")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->find(Article::class, $id);
        if ($article === null) {
            throw $this->createNotFoundException("L'article ${id} n'existe pas");
        }
        return $this->render('article/show.html.twig', ['article' => $article]);
    }
}