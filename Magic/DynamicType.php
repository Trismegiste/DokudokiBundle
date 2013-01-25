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

    const classKey = '-magic';


    /**
     * An alias for the type of this object
     */
    function getClassName();
}
