<?php
namespace AppBundle\EventListener;


use AppBundle\Entity\Article;
use Doctrine\ORM\Event\LifecycleEventArgs;

class SearchIndexer
{
    private $redis;

    // Instanciation de redis
    public function __construct($redis)
    {
        $this->redis = $redis;
    }

    // PostPersist = Après l'enregistrement.
    public function postPersist(LifecycleEventArgs $args)
    {
        // Je récupère l'objet
        $object = $args->getObject();
        // Est-ce une entité de type Article ?
        if (!$object instanceof Article) {
            return;
        }
        // Si oui, je l'ajout dans le "set" articles, pour nom "TITRE:ID".
        $this->redis->sadd('articles', $object->getTitle() . ':' . $object->getId());
    }
}