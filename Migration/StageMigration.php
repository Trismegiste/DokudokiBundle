<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Migration;

use Trismegiste\DokudokiBundle\Transform\Mediator\Mediator;
use Trismegiste\DokudokiBundle\Persistence\RepositoryInterface;

/**
 * StageMigration is a Template Method pattern for creating a migration
 * service.
 *
 * @author flo
 */
abstract class StageMigration
{

    protected $collection;
    private $mediator;

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
        $cardinal = 0;
        $iter = $this->collection->find();
        foreach ($iter as $struc) {
            $old = $src->createFromDb($struc);
            $dst->persist($old);
            $cardinal++;
        }

        return $cardinal;
    }

}