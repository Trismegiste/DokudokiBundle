<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator;

use Trismegiste\DokudokiBundle\Utils\ReflectionClassBC;

/**
 * Design Pattern : Mediator
 * Component : Mediator
 * 
 * Mediator is the delegation of Factory to recursively traverse
 * objects and arrays. Responsible for maintaining a list of Colleague (the
 * mappers converting object, array, scalar, MongoDate, etc. )
 * 
 * @author flo
 */
class Mediator implements RecursiveMapper, TypeRegistry
{
    const FQCN_KEY = '-class';

    protected $mappingColleague = array();

    /**
     * {@inheritDoc}
     */
    public function registerType($name, Mapping $colleague)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('The key type is not a string');
        }

        $this->mappingColleague[$name] = $colleague;
    }

    /**
     * {@inheritDoc}
     */
    public function recursivDesegregate($obj)
    {
        $stratKey = $this->getType($obj);

        if (array_key_exists($stratKey, $this->mappingColleague)) {
            return $this->mappingColleague[$stratKey]->mapToDb($obj);
        } else {
            throw new \DomainException("Unsupported type $stratKey");
        }
    }

    /**
     * {@inheritDoc}
     */
    public function recursivCreate($param)
    {
        $stratKey = $this->getType($param);

        if (array_key_exists($stratKey, $this->mappingColleague)) {
            return $this->mappingColleague[$stratKey]->mapFromDb($param);
        } else {
            throw new \DomainException("Unsupported type $stratKey");
        }
    }

    protected function getType($param)
    {
        $default = gettype($param);
        if ('object' == $default) {
            if (array_key_exists(get_class($param), $this->mappingColleague)) {
                $default = get_class($param);
            }
        }

        return $default;
    }

}

/*

II] Avec interface
+ Transient permet de zapper une classe
+ Cleanable permet de faire le menage

III] Managing class not found
exception
stdClass
Automagic

*/