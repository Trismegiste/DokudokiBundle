<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform;

/**
 * A contract to create object from array and array from object
 * 
 * @author flo
 */
interface FactoryInterface
{

    /**
     * Transform objects into array by adding a key for the FQCN
     *
     * @param object $obj the object to dump
     * 
     * @return array the dumped tree
     * 
     * @throws \LogicException If $obj is not an object
     */
    function desegregate($obj);

    /**
     * Restore the full tree of a rich document with the desegregated dump
     *
     * @param array $dump the tree representing a full structured object & array
     * 
     * @return object the created object(s)
     * 
     * @throws \LogicException If the tree does not contains a key for its FQCN
     */
    function create(array $dump);
}
