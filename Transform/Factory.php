<?php

/*
 * DokudokiBundle ◕ ‿‿﻿ ◕
 */

namespace Trismegiste\DokudokiBundle\Transform;

use Trismegiste\DokudokiBundle\Transform\Mediator;
use Trismegiste\DokudokiBundle\Transform\Mediator\MapObject;

/**
 * Factory is a transformer/factory to go from object to array and vice versa
 *
 * @author florent
 */
class Factory
{

    protected $desegregateAlgo;

    public function __construct()
    {
        $algo = new Mediator\Mediator();
        $algo->registerType(array('NULL', 'resource'), new Mediator\MapNullable($algo));
        $algo->registerType(
                array(
            'boolean',
            'integer',
            'double',
            'string'
                ), new Mediator\MapScalar($algo)
        );
        $algo->registerType('array', new Mediator\MapArray($algo));
        $algo->registerType('object', new Mediator\MapObject($algo));
        $this->desegregateAlgo = $algo;
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

        return $this->desegregateAlgo->recursivDesegregate($obj);
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

        return $this->recursivCreate($dump);
    }

    /**
     * Recursion for restoration
     *
     * @param array $param
     * @return array
     */
    private function recursivCreate(array $param)
    {
        $modeObj = isset($param[MapObject::FQCN_KEY]);

        if ($modeObj) {
            $fqcn = $param[MapObject::FQCN_KEY];
            unset($param[MapObject::FQCN_KEY]);
            $reflector = new \ReflectionClass($fqcn);
            $vectorOrObject = self::createInstanceWithoutConstructor($reflector);
        } else {
            $vectorOrObject = array();
        }

        foreach ($param as $key => $val) {
            if (is_array($val)) {
                // go deeper
                $mapped = $this->recursivCreate($val);
            } else {
                $mapped = $val;
            }

            // set the value
            if ($modeObj) {
                if ($reflector->hasProperty($key)) {
                    $prop = $reflector->getProperty($key);
                    $prop->setAccessible(true);
                    $prop->setValue($vectorOrObject, $mapped);
                } else {
                    // injecting schemaless property
                    $vectorOrObject->$key = $val;
                }
            } else {
                $vectorOrObject[$key] = $mapped;
            }
        }

        return $vectorOrObject;
    }

    /**
     * Fix for PHP version < 5.4
     * @param type $class
     * @return type
     */
    static protected function createInstanceWithoutConstructor(\ReflectionClass $reflector)
    {
        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            return $reflector->newInstanceWithoutConstructor();
        } else {
            $class = $reflector->getName();
            $serealized = "O:" . strlen($class) . ":\"$class\":" . '0:{}';
            return unserialize($serealized);
        }
    }

}