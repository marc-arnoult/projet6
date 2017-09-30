<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $redis = $this->get('snc_redis.default');

        $userNear = $redis->georadiusbymember(
            'user_position', // en premier paramètre nous passons la "clé" où sont stocké nos utilisateurs.
            'user:3', // puis on veut tous les utilisateurs près de "user:3".
            10, // sur un rayon de 10km.
            'km',
            //Petite remarque n'oubliez pas de passer l'argument à true.
            ['WITHDIST' => true] // On veut aussi récupérer la distance.
        );

        return $this->render('default/index.html.twig', ['userNear' => $userNear]);
    }
}
