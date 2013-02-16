<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Migration;

use Trismegiste\DokudokiBundle\Transform\Mediator\Mediator;
use Trismegiste\DokudokiBundle\Persistence\RepositoryInterface;

/**
 * StageMigration is ...
 *
 * @author flo
 */
abstract class StageMigration
{

    protected $collection;
    protected $mediator;

    public function __construct(\MongoCollection $coll)
    {
        $this->collection = $coll;
        $this->mediator = new \Trismegiste\DokudokiBundle\Transform\Mediator\Mediator();
        $this->buildMapper($this->mediator);
    }

    abstract protected function buildMapper(Mediator $algo);

    public function analyse()
    {
        $cardinal = 0;
        $iter = $this->collection->find();
        foreach ($iter as $struc) {
            $this->mediator->recursivCreate($struc);
            $cardinal++;
        }

        return $cardinal;
    }

    public function migrate(RepositoryInterface $src, RepositoryInterface $dst)
    {
        $iter = $this->collection->find();
        foreach ($iter as $struc) {
            $pk = $struc['_id'];
            $old = $src->findByPk((string) $pk);
            $dst->persist($old);
        }
    }

}