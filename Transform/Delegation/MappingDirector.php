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
 * SRP : Knows the order to build the chain of mapping
 *
 * @author flo
 */
class MappingDirector
{

    public function create(MappingBuilder $builder)
    {
        //     $builder = new Stage\First();  // injected in parameter
        $algo = $builder->createChain();
        $builder->createDbSpecific($algo);
        $builder->createObject($algo);
        $builder->createNonObject($algo);

        return $algo;
    }

}
