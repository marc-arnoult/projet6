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

        // en premier paramètre nous passons la "clé" où sont nos utilisateurs.
        // puis on veut tous les utilisateurs près de "user:3" sur un rayon de 10km.
        $userNear = $redis->georadiusbymember(
            'user_position',
            'user:3',
            10,
            'km',
            //Petite remarque n'oubliez pas de passer l'argument à true
            ['WITHDIST' => true]
        );

        return $this->render('default/index.html.twig', ['userNear' => $userNear]);
    }
}
