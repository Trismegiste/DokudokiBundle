<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Delegation\Stage;

use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague;
use Trismegiste\DokudokiBundle\Transform\Mediator\TypeRegistry;


/**
 * Design Pattern : Builder
 * Component : Builder (concrete) 
 * 
 * This is a first release of a builder
 *
 * @author flo
 */
class First extends AbstractStage
{

    /* 
     * this is where you need to inject specific configuration in the 
     * constructor
     */
    
    public function createObject(TypeRegistry $algo)
    {
        new Colleague\MapMagic($algo);
        new Colleague\MapSkippable($algo);
        new Colleague\MapObject($algo);
    }

}