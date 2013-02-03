<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Delegation;

use Trismegiste\DokudokiBundle\Transform\Mediator\Mediator;
use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague;

/**
 * Design Pattern : Builder
 * Component : Builder (abstract) 
 * 
 * This is a template for a builder of delegation of mapping 
 * @see Transformer 
 *
 * @author flo
 */
class MappingBuilder
{

    public function createChain()
    {
        return new Mediator();
    }

    public function createNonObject(Mediator $algo)
    {
        new Colleague\MapNullable($algo);
        new Colleague\MapScalar($algo);
        new Colleague\MapArray($algo);
    }

    public function createObject(Mediator $algo)
    {
        new Colleague\MapObject($algo);
        new Colleague\MapSkippable($algo);
        new Colleague\MapMagic($algo);
    }

    public function createDbSpecific(Mediator $algo)
    {
        new Colleague\DateObject($algo);
        new Colleague\MongoBinData($algo);
    }

}