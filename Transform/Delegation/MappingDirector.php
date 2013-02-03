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

    public function create(/* builder */)
    {
        $builder = new Stage\First();  // injected in parameter
        $algo = $builder->createChain();
        $builder->createNonObject($algo);
        $builder->createObject($algo);
        $builder->createDbSpecific($algo);

        return $algo;
    }

}
