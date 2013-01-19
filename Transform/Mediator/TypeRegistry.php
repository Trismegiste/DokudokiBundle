<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator;

/**
 * Contract for registering a type name with a mapper object
 * 
 * @author flo
 */
interface TypeRegistry
{

    /**
     * Register the type name (php) with a mapper object
     * 
     * @param string $name the php type name (@see gettype)
     * @param Mapping $colleague the mapper (usually a colleague from a Mediator Pattern
     */
    function registerType($name, Mapping $colleague);
}
