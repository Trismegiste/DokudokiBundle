<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Delegation\Stage;

use Trismegiste\DokudokiBundle\Transform\Delegation\MappingBuilder;
use Trismegiste\DokudokiBundle\Transform\Mediator\Mediator;
use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague;

/**
 * Design Pattern : Builder
 * Component : Builder (concrete) 
 * 
 * This is a first release of a builder
 *
 * @author flo
 */
class First extends MappingBuilder
{

    /* 
     * this is where you need to inject specific configuration in the 
     * constructor
     */
    
    public function createObject(Mediator $algo)
    {
        new Colleague\MapObject($algo);
        new Colleague\MapSkippable($algo);
        new Colleague\MapMagic($algo);
    }

}