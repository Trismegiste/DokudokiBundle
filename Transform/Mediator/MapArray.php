<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator;

use Trismegiste\DokudokiBundle\Utils\ReflectionClassBC;

/**
 * MapArray is a mapper to and from an array
 *
 * @author florent
 */
class MapArray extends AbstractMapper
{

    public function mapFromDb($param)
    {
        $modeObj = isset($param[MapObject::FQCN_KEY]);

        if ($modeObj) {
            $fqcn = $param[MapObject::FQCN_KEY];
            unset($param[MapObject::FQCN_KEY]);
            $reflector = new ReflectionClassBC($fqcn);
            $vectorOrObject = $reflector->newInstanceWithoutConstructor();
        } else {
            $vectorOrObject = array();
        }

        foreach ($param as $key => $val) {
            // go deeper
            $mapped = $this->mediator->recursivCreate($val);

            // set the value
            if ($modeObj) {
                if ($reflector->hasProperty($key)) {
                    $prop = $reflector->getProperty($key);
                    $prop->setAccessible(true);
                    $prop->setValue($vectorOrObject, $mapped);
                } else {
                    // injecting schemaless property
                    $vectorOrObject->$key = $mapped;
                }
            } else {
                $vectorOrObject[$key] = $mapped;
            }
        }

        return $vectorOrObject;
    }

    public function mapToDb($arr)
    {
        $dump = array();
        foreach ($arr as $key => $val) {
            // go depper
            $dump[$key] = $this->mediator->recursivDesegregate($val);
        }

        return $dump;
    }

    protected function getResponsibleType()
    {
        return array('array');
    }

}