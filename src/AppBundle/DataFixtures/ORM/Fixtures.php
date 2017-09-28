<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Fixtures implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $redis = $this->container->get('snc_redis.default');
        // Création de 20 utilisateurs
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setPseudo(sprintf("user:%d", $i));
            $user->setPassword("pass");

            $longitude = 2.333333 + ($i * 0.05);
            $latitude = 48.866667 + ($i * 0.05);

            // Ajout des utilisateurs dans redis avec position GPS et pseudo.
            $redis->geoadd(
                'user_position',
                $longitude,
                $latitude,
                $user->getPseudo()
            );
            $manager->persist($user);
        }
        // Enregistrement des utilisateurs en base de données.
        $manager->flush();
    }
}