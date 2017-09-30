<?php

namespace AppBundle\Controller;

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
        $titre = $request->query->get('title');
        $redis = $this->get('snc_redis.default');
        // Ici se place toute notre logique de recherche.
        // [ => recherche inclusif du titre.
        $result = $redis->zrangebylex('article', "[$titre", "[$titre\xff", ['LIMIT', 0, 10]);

        // On renvoi le résulat en json avec le code HTTP et les headers adaptés.
        return new JsonResponse($result, 200, ['Content-Type' => 'application/json']);
    }
}
