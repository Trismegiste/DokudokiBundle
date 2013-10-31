<?php

/*
 * Yuurei
 */

namespace Trismegiste\Yuurei\Transform\Delegation;

/**
 * Design Pattern : Builder
 * Component : Director
 *
 * This director builds the Mediator and the chain of Mapper
 *
 * SRP : Knows the order to build the chain of mapping
 */
class MappingDirector
{

    /**
     * Builds the mediator for mapping with the help of builder
     *
     * @param MappingBuilder $builder
     * 
     * @return TypeRegistry
     */
    public function create(MappingBuilder $builder)
    {
        $algo = $builder->createChain();
        $builder->createDbSpecific($algo);
        $builder->createObject($algo);
        $builder->createNonObject($algo);
        $builder->createBlackHole($algo);

        return $algo;
    }

}
