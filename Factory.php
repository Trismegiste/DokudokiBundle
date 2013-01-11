<?php

/*
 * DokudokiBundle ◕ ‿‿﻿ ◕
 */

namespace Trismegiste\DokudokiBundle;

/**
 * Factory is a transformer/factory to go from object to array and vice versa
 *
 * @author florent
 */
class Factory
{

    const FQCN_KEY = '_class';

    /**
     * Transform objects into array by adding a key for the FQCN
     *
     * @param object $obj the object to dump
     * @return array the dumped tree
     * @throws \LogicException If $obj is not an object
     */
    public function desegregation($obj)
    {
        if (!is_object($obj)) {
            throw new \LogicException('Only object can be transformed into tree');
        }

        $result = var_export($obj, true);
        $result = preg_replace('#([A-Z][_A-Za-z0-9\\\\]+)::__set_state\(array\(#', '((array) array("' . self::FQCN_KEY . '" => "$1", ', $result);
        eval('$dump = ' . $result . ';');

        return $dump;
    }

    /**
     * Restore the full tree of a rich document with the desegregated dump
     *
     * @param array $dump the tree represtenting a full structured object & array
     * @return object the created object(s)
     * @throws \LogicException
     */
    public function create(array $dump)
    {
        if (!array_key_exists(self::FQCN_KEY, $dump)) {
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
        $modeObj = isset($param[self::FQCN_KEY]);

        if ($modeObj) {
            $fqcn = $param[self::FQCN_KEY];
            unset($param[self::FQCN_KEY]);
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
                $prop = $reflector->getProperty($key);
                $prop->setAccessible(true);
                $prop->setValue($vectorOrObject, $mapped);
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