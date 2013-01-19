<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator;

/**
 * A contract for a mapper
 * 
 * @author florent
 */
interface Mapping
{

    /**
     * Convert a variable to a persistable structure (typically an array)
     * It feels like serialization
     * 
     * @param mixed $var 
     * 
     * @return mixed
     */
    function mapToDb($var);

    /**
     * Convert a variable coming from a persistance layer to its representation in memory
     * It feels like un-serialization
     * 
     * @param mixed $var 
     * 
     * @return mixed
     */
    function mapFromDb($var);
}
