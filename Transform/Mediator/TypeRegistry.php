<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator;

/**
 * Contract for regitering a type namme with a mapper object
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
