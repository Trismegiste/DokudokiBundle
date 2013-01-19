<?php

/*
 * DokudokiBundle ◕ ‿‿﻿ ◕
 */

namespace Trismegiste\DokudokiBundle\Transform;

use Trismegiste\DokudokiBundle\Transform\Mediator;
use Trismegiste\DokudokiBundle\Transform\Mediator\MapObject;

/**
 * Factory is a transformer/factory to move from object to array and vice versa
 *
 * @author florent
 */
class Factory
{

    protected $delegation;

    public function __construct()
    {
        $algo = new Mediator\Mediator();
        new Mediator\MapNullable($algo);
        new Mediator\MapScalar($algo);
        new Mediator\MapArray($algo);
        new Mediator\MapObject($algo);
        new Mediator\Cast\MongoDate($algo);        
        new Mediator\Cast\MongoBinData($algo);        
        $this->delegation = $algo;
    }

    /**
     * Transform objects into array by adding a key for the FQCN
     *
     * @param object $obj the object to dump
     * @return array the dumped tree
     * @throws \LogicException If $obj is not an object
     */
    public function desegregate($obj)
    {
        if (!is_object($obj)) {
            throw new \LogicException('Only object can be transformed into tree');
        }

        return $this->delegation->recursivDesegregate($obj);
    }

    /**
     * Restore the full tree of a rich document with the desegregated dump
     *
     * @param array $dump the tree representing a full structured object & array
     * @return object the created object(s)
     * @throws \LogicException
     */
    public function create(array $dump)
    {
        if (!array_key_exists(MapObject::FQCN_KEY, $dump)) {
            throw new \LogicException('There is no key for the FQCN of the root entity');
        }

        return $this->delegation->recursivCreate($dump);
    }

}