<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Magic;

/**
 * Represents a dynamic class and its dynamic content
 *
 * @author florent
 */
interface DynamicType extends \IteratorAggregate
{

    const classKey = '-class';

    /**
     * An alias for the type of this object
     */
    function getClassName();
}
