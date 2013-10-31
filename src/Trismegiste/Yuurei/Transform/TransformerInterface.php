<?php

/*
 * Yuurei
 */

namespace Trismegiste\Yuurei\Transform;

/**
 * A contract to create object from array and array from object
 * 
 * @author flo
 */
interface TransformerInterface
{

    /**
     * Transform objects into array by adding a key for the FQCN
     * Goal : storing into a NoSQL database
     *
     * @param object $obj the object to dump
     * 
     * @return array the dumped tree
     * 
     * @throws \LogicException If $obj is not an object
     */
    function desegregate($obj);

    /**
     * Restore a complex object of a rich document with its desegregated dump
     * Goal : restoring from a NoSQL database
     *
     * @param array $dump the tree representing a full structured object & array
     * 
     * @return object the created object(s)
     * 
     * @throws \LogicException If the tree does not contains a key for its FQCN
     */
    function create(array $dump);
}
