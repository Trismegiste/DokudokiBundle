<?php

/*
 * Yuurei
 */

namespace Trismegiste\Yuurei\Transform\Mediator;

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
     * @param Mapping $colleague the mapper (usually a colleague from a Mediator Pattern
     */
    function registerType(Mapping $colleague);
}
