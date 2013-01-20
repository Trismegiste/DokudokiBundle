<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator;

/**
 * MapObject is a mapper to and from an object
 *
 * @author florent
 */
class MapObject extends AbstractMapper
{

    /**
     * {@inheritDoc}
     */
    public function mapFromDb($var)
    {
        // fallback for objects (Mongo types for example)
        return $var;
    }

    /**
     * {@inheritDoc}
     */
    public function mapToDb($obj)
    {
        $reflector = new \ReflectionObject($obj);
        $className = $reflector->getName();
        $dump = array();
        $dump[Mediator::FQCN_KEY] = $className;
        foreach ($reflector->getProperties() as $prop) {
            if (!$prop->isStatic()) {
                $prop->setAccessible(true);
                // go deeper
                $dump[$prop->name] = $this->mediator->recursivDesegregate($prop->getValue($obj));
            }
        }

        return $dump;
    }

    /**
     * {@inheritDoc}
     */
    protected function getResponsibleType()
    {
        return array('object');
    }

}