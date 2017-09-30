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
            'mon premier article',
            'mon deuxieme article',
            'mon troisieme article',
            'article factice',
            'article redondant'
        ];

        // Création de 5 articles.
        for ($i = 0; $i < 5; $i++) {
            $article = new Article();
            $article->setContent('Post hoc impie perpetratum quod in aliis quoque iam timebatur, tamquam licentia crudelitati indulta per suspicionum nebulas aestimati quidam noxii damnabantur. quorum pars necati, alii puniti bonorum multatione actique laribus suis extorres nullo sibi relicto praeter querelas et lacrimas, stipe conlaticia victitabant, et civili iustoque imperio ad voluntatem converso cruentam, claudebantur opulentae domus et clarae.');
            $article->setTitle($titles[$i]);
            $article->setCreatedAt(new \DateTime('now'));

            // Ajout dans le sorted set 'article' de nos titres, on donne pour chacun de nos titres un score de 0.
            $redis->zadd('article', [$titles[$i] => 0]);
            $manager->persist($article);
        }
        // Enregistrement des articles en base de données.
        $manager->flush();
    }
}