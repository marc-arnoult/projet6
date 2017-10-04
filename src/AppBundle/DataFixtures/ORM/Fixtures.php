<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Article;
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

        // Titres des mes article, important pour notre recherche avec autocomplétion.
        $titles = [
            'tout savoir sur le développement',
            '50 nuances de projet 2',
            'développeur une passion',
            'un projet pas comme les autres',
            'les projets, toute une histoire',
            'développement web, bon à savoir'
        ];

        // Création de 6 articles.
        for ($i = 0; $i < 6; $i++) {
            $article = new Article();
            $article->setContent('Post hoc impie perpetratum quod in aliis quoque iam timebatur, tamquam licentia crudelitati indulta per suspicionum nebulas aestimati quidam noxii damnabantur. quorum pars necati, alii puniti bonorum multatione actique laribus suis extorres nullo sibi relicto praeter querelas et lacrimas, stipe conlaticia victitabant, et civili iustoque imperio ad voluntatem converso cruentam, claudebantur opulentae domus et clarae.');
            $article->setTitle($titles[$i]);
            $article->setCreatedAt(new \DateTime('now'));

            // Enregistrement des articles en base de données.
            $manager->persist($article);
            $manager->flush();

            /*
            * J'utilise ici les "sets" pour enregistrer mes articles dans la clé "articles"
            * Noté la concaténation avec l'id pour ensuite le récupérer plus tard pour la récupération d'un article.
            */
            $redis->sadd('articles', $titles[$i] . ':' . $article->getId());
        }
    }
}