<?php

/*
 * Yuurei
 */

namespace Trismegiste\Yuurei\Transform\Delegation\Stage;

use Trismegiste\Yuurei\Transform\Mediator\Mediator;
use Trismegiste\Yuurei\Transform\Mediator\Colleague;
use Trismegiste\Yuurei\Transform\Delegation\MappingBuilder;
use Trismegiste\Yuurei\Transform\Mediator\TypeRegistry;

/**
 * Design Pattern : Builder
 * Component : Builder (abstract)
 *
 * This is a template for a builder of delegation of mapping
 * @see Transformer
 *
 * @author flo
 */
abstract class AbstractStage implements MappingBuilder
{

    /**
     * {@inheritDoc}
     */
    public function createChain()
    {
        return new Mediator();
    }

    /**
     * {@inheritDoc}
     */
    public function createNonObject(TypeRegistry $algo)
    {
        new Colleague\MapArray($algo);
        new Colleague\MapScalar($algo);
        new Colleague\MapNullable($algo);
    }

    /**
     * {@inheritDoc}
     */
    public function createDbSpecific(TypeRegistry $algo)
    {
        new Colleague\DateObject($algo);
        new Colleague\MongoInvariant($algo);
    }

    /**
     * {@inheritDoc}
     * Default adapter for implementation of the interface
     */
    public function createBlackHole(TypeRegistry $algo)
    {

    }

}