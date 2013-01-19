<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator;

use Trismegiste\DokudokiBundle\Utils\ReflectionClassBC;

/**
 * Design Pattern : Mediator
 * Component : Colleague (concrete)
 * 
 * MapArray deals the mapping with arrays
 *
 * @author florent
 */
class MapArray extends AbstractMapper
{

    /**
     * Map an array from the db to an object
     * 
     * @param array $param
     * 
     * @return object 
     */
    protected function mapFromDbToObject($param)
    {
        $fqcn = $param[MapObject::FQCN_KEY];
        unset($param[MapObject::FQCN_KEY]);
        $reflector = new ReflectionClassBC($fqcn);
        $obj = $reflector->newInstanceWithoutConstructor();

        foreach ($param as $key => $val) {
            // go deeper
            $mapped = $this->mediator->recursivCreate($val);
            // set the value
            if ($reflector->hasProperty($key)) {
                $prop = $reflector->getProperty($key);
                $prop->setAccessible(true);
                $prop->setValue($obj, $mapped);
            } else {
                // If no property in the class, injecting the property in the object anyway
                $obj->$key = $mapped;
            }
        }
        // @todo Is it necessary for PHP 5.4 ?
        return unserialize(serialize($obj));
    }

    /**
     * Map an array from the db to an array (with recursion)
     * 
     * @param array $param
     * 
     * @return array
     */
    protected function mapFromDbToArray($param)
    {
        return array_map(array($this->mediator, 'recursivCreate'), $param);
    }

    /**
     * {@inheritDoc}
     */
    public function mapFromDb($param)
    {
        $modeObj = isset($param[MapObject::FQCN_KEY]);
        return ($modeObj) ? $this->mapFromDbToObject($param) : $this->mapFromDbToArray($param);
    }

    /**
     * {@inheritDoc}
     */
    public function mapToDb($arr)
    {
        return array_map(array($this->mediator, 'recursivDesegregate'), $arr);
    }

    /**
     * {@inheritDoc}
     */
    protected function getResponsibleType()
    {
        return array('array');
    }

}