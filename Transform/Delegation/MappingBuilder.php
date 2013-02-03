<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Delegation;

use Trismegiste\DokudokiBundle\Transform\Mediator\TypeRegistry;

/**
 * Design Pattern : Builder
 * Component : Builder (abstract) 
 * 
 * This is a contract for a builder of delegation of mapping 
 * @see Mediator 
 *
 * @author flo
 */
interface MappingBuilder
{

    /**
     * Create the chain of algorithms of mapping
     * 
     * @return TypeRegistry 
     */
    function createChain();

    /**
     * Register mappers for non-object (scalar, null, array...)
     * 
     * @param TypeRegistry $algo 
     */
    function createNonObject(TypeRegistry $algo);

    /**
     * Register mappers for object
     * 
     * @param TypeRegistry $algo 
     */
    function createObject(TypeRegistry $algo);

    /**
     * Register mappers for database conversion (date, blob...)
     * 
     * @param TypeRegistry $algo 
     */
    function createDbSpecific(TypeRegistry $algo);
}