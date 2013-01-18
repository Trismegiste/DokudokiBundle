<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Strategy;

/**
 * MapObject is a ...
 *
 * @author florent
 */
class MapObject implements Mapping
{

    public function mapFromDb($var)
    {

    }

    public function mapToDb($obj)
    {
        $reflector = new \ReflectionObject($obj);
        $className = $reflector->getName();
        $dump = array();
        $dump[self::FQCN_KEY] = $className;
        foreach ($reflector->getProperties() as $prop) {
            if (!$prop->isStatic()) {
                $prop->setAccessible(true);
                // go depper
                $dump[$prop->name] = $this->recursivDesegregate($prop->getValue($obj));
            }
        }

        return $dump;
    }

}