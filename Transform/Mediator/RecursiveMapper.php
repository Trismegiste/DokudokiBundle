<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator;

/**
 * Contract for recursive mapping to and from a database
 * 
 * @author flo
 */
interface RecursiveMapper
{

    /**
     * Recursion for desegregation (or untyping/casting to array)
     *
     * @param mixed $obj
     * @return mixed
     */
    function recursivDesegregate($obj);

    /**
     * Recursion for restoration
     *
     * @param mixed $param
     * @return mixed
     */
    function recursivCreate($param);
}
