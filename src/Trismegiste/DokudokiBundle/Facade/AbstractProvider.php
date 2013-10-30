<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Facade;

use Trismegiste\DokudokiBundle\Transform\Mediator\RecursiveMapper;
use Trismegiste\DokudokiBundle\Persistence\Repository;
use Trismegiste\DokudokiBundle\Transform\Delegation\MappingBuilder;
use Trismegiste\DokudokiBundle\Persistence\Logger;

/**
 * Design Pattern: Template Method
 * AbstractProvider is an implementation of the factory method
 * with injection of required service
 *
 * @author flo
 */
abstract class AbstractProvider implements ProviderInterface
{

    protected $collection;

    public function __construct(\MongoCollection $coll)
    {
        $this->collection = $coll;
    }

    /**
     * {@inheritDoc}
     */
    public function createRepository(MappingBuilder $builder)
    {
        $director = $this->createDirector();
        $mediator = $director->create($builder);
        $transform = $this->createTransformer($mediator);

        return new Repository($this->collection, $transform);
    }

    /**
     * Creates the director for building the mapping chain
     *
     * @return MappingDirector
     */
    abstract protected function createDirector();

    /**
     * Creates the Transformer with the delegation to RecursiveMapper
     *
     * @return TransformerInterface
     */
    abstract protected function createTransformer(RecursiveMapper $map);
}