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

    const TO_DB = 222;
    const FROM_DB = 538;

    /**
     * Register the type name (php) with a mapper object
     *
     * @param int $way TypeRegistry::TO_DB or TypeRegistry::FROM_DB to write to db or to read rom DB
     * @param string $name the php type name (@see gettype)
     * @param Mapping $colleague the mapper (usually a colleague from a Mediator Pattern
     */
    function registerType($way, $name, Mapping $colleague);
}
