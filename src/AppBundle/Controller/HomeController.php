<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/search", name="search")
     * @Method("GET")
     */
    public function searchAction(Request $request)
    {
        // Récupération de la valeur passé en paramètre.
        // htmlspecialchar => pour se proteger contre les failles XSS.
        // strtolower pour tout mettre en minuscule.
        $titre = htmlspecialchars(strtolower($request->query->get('title')));
        $redis = $this->get('snc_redis.default');
        // Ici se place toute notre logique de recherche.
        /*
         * Je scan dans mon set articles la chaine de caractère envoyé par l'utilisateur.
         * Je regarde si il y a un "match" avec le titre et je récupère le tout.
         */
        $data = $redis->sscan('articles', 0, ['MATCH' => '*' . $titre . '*']);
        $articles = [];

        // Ici je récupère l'id et le titre dans 2 variables distincte pour ensuite les traiter côté front-end.
        foreach ($data[1] as $article) {
            list($title, $id) = explode(':', $article);
            $articles[$id] = $title;
        }
        // On renvoi le résulat en json avec le code HTTP et les headers adaptés.
        return new JsonResponse($articles, 200, ['Content-Type' => 'application/json']);
    }
}
