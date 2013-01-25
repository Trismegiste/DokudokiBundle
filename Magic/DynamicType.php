<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Magic;

/**
 * Represents a dynamic class and its content
 *
 * @author florent
 */
interface DynamicType
{

    const classKey = '_cls';

    /**
     * Get this object in an php tree-structured array by transforming the
     * DynamicType instances with the help of className. Recursively.
     *
     * @return array the tree represents this object
     */
    function getUnTyped();

    /**
     * An alias for the type of this object
     */
    function getClassName();
}
