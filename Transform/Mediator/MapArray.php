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

    protected function mapFromDbToObject($param)
    {
        $fqcn = $param[MapObject::FQCN_KEY];
        unset($param[MapObject::FQCN_KEY]);
        $reflector = new ReflectionClassBC($fqcn);
        $vectorOrObject = $reflector->newInstanceWithoutConstructor();

        foreach ($param as $key => $val) {
            // go deeper
            $mapped = $this->mediator->recursivCreate($val);

            // set the value
            if ($reflector->hasProperty($key)) {
                $prop = $reflector->getProperty($key);
                $prop->setAccessible(true);
                $prop->setValue($vectorOrObject, $mapped);
            } else {
                // injecting schemaless property
                $vectorOrObject->$key = $mapped;
            }
        }

        return $vectorOrObject;
    }

    protected function mapFromDbToArray($param)
    {
        return array_map(array($this->mediator, 'recursivCreate'), $param);
    }

    public function mapFromDb($param)
    {
        $modeObj = isset($param[MapObject::FQCN_KEY]);
        return ($modeObj) ? $this->mapFromDbToObject($param) : $this->mapFromDbToArray($param);
    }

    public function mapToDb($arr)
    {
        return array_map(array($this->mediator, 'recursivDesegregate'), $arr);
    }

    protected function getResponsibleType()
    {
        return array('array');
    }

}