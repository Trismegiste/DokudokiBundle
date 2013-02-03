<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Delegation;

use Trismegiste\DokudokiBundle\Transform\Mediator\Mediator;
use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague;

/**
 * Design Pattern : Builder
 * Component : Director 
 * 
 * This director builds the Mediator and the chain of Mapper 
 *
 * @author flo
 */
class MappingDirector
{

    public function create()
    {
        $algo = new Mediator();
        new Colleague\MapNullable($algo);
        new Colleague\MapScalar($algo);
        new Colleague\MapArray($algo);
        new Colleague\MapObject($algo);
        new Colleague\MapSkippable($algo);
        new Colleague\MapMagic($algo);
        new Colleague\DateObject($algo);
        new Colleague\MongoBinData($algo);

        return $algo;
    }

}
