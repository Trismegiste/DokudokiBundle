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
     * @param string $fqcn full qualified class name
     * @param array $param properties of object
     * 
     * @return object 
     */
    protected function mapFromDbToObject($fqcn, $param)
    {
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
        $reflector->fixHackBC($obj);

        return $obj;
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
        if (array_key_exists(Mediator::FQCN_KEY, $param)) {
            $fqcn = $param[Mediator::FQCN_KEY];
            if (!class_exists($fqcn)) {
                throw new \DomainException("Cannot restore a '$fqcn' : class does not exist");
            }
            unset($param[Mediator::FQCN_KEY]);
            return $this->mapFromDbToObject($fqcn, $param);
        } else {
            return $this->mapFromDbToArray($param);
        }
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