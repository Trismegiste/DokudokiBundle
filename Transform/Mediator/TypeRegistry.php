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

    const CREATE = 1;
    const DESEGREGATE = 2;

    /**
     * Register the type name (php) with a mapper object
     *
     * @param int $way TypeRegistry::CREATE or TypeRegistry::DESEGREGATE to create object from array or to "untyping" object to array
     * @param string $name the php type name (@see gettype)
     * @param Mapping $colleague the mapper (usually a colleague from a Mediator Pattern
     */
    function registerType($way, $name, Mapping $colleague);
}
