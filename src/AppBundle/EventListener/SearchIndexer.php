<?php
namespace AppBundle\EventListener;


use AppBundle\Entity\Article;
use Doctrine\ORM\Event\LifecycleEventArgs;

class SearchIndexer
{
    private $redis;

    public function __construct($redis)
    {
        $this->redis = $redis;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if (!$object instanceof Article) {
            return;
        }

        $this->redis->sadd('articles', $object->getTitle() . ':' . $object->getId());
    }
}