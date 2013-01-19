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
     * Recursion for restoration. It is a factory of objects (at least the root
     * is an object). FQCN are stored in a key (@see MapObject)
     *
     * @param mixed $param
     * @return mixed
     */
    function recursivCreate($param);
}
