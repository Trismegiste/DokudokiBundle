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
 * No magic and FQCN in database for class keys
 *
 * @author flo
 */
class ZeroConfigNoMagic extends AbstractStage
{

    public function createObject(TypeRegistry $algo)
    {
        new Colleague\MapSkippable($algo);
        new Colleague\MapObject($algo);
    }

}