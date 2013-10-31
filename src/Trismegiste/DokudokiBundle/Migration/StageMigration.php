<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Migration;

use Trismegiste\Yuurei\Transform\Mediator\Mediator;
use Trismegiste\Yuurei\Persistence\RepositoryInterface;

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
        $this->mediator = new Mediator();
        $this->buildMapper($this->mediator);
    }

    /**
     * Build the chain of Mapping contained in a Mediator
     */
    abstract protected function buildMapper(Mediator $algo);

    /**
     * Collect informations from entities in the collection with the mediator
     * @return int number of analyzed root entities
     */
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

    /**
     * Do a migration from one stage to another
     * 
     * @param RepositoryInterface $src
     * @param RepositoryInterface $dst
     * @return int number of migrated root entities
     */
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