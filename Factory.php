<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle;

/**
 * Factory is a ...
 *
 * @author florent
 */
class Factory
{

    public function desegregation($obj)
    {
        if (!is_object($obj)) {
            throw new \LogicException('You fail');
        }

        $result = var_export($obj, true);
        $result = preg_replace('#([_A-Za-z0-9\\\\]+)::__set_state\(array\(#', '((array) array("_cls" => "$1", ', $result);
        eval('$dump = ' . $result . ';');

        return $dump;
    }

    public function create(array $dump)
    {
        return $this->recursivCreate($dump);
    }

    private function recursivCreate(array $param)
    {
        $modeObj = isset($param['_cls']);

        if ($modeObj) {
            $fqcn = $param['_cls'];
            unset($param['_cls']);
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
     * Copy/Paste from http://fr2.php.net/manual/en/reflectionclass.newinstancewithoutconstructor.php
     *
     * @param type $class
     * @return type
     */
    static protected function createInstanceWithoutConstructor(\ReflectionClass $reflector)
    {
        $class = $reflector->getName();
        if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
            return $reflector->newInstanceWithoutConstructor();
        } else {
            $properties = $reflector->getProperties();
            $defaults = $reflector->getDefaultProperties();

            $serealized = "O:" . strlen($class) . ":\"$class\":" . count($properties) . ':{';
            foreach ($properties as $property) {
                $name = $property->getName();
                if ($property->isProtected()) {
                    $name = chr(0) . '*' . chr(0) . $name;
                } elseif ($property->isPrivate()) {
                    $name = chr(0) . $class . chr(0) . $name;
                }
                $serealized .= serialize($name);
                if (array_key_exists($property->getName(), $defaults)) {
                    $serealized .= serialize($defaults[$property->getName()]);
                } else {
                    $serealized .= serialize(null);
                }
            }
            $serealized .="}";
            return unserialize($serealized);
        }
    }

}