<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator\Colleague;

use Trismegiste\DokudokiBundle\Transform\Skippable;
use Trismegiste\DokudokiBundle\Transform\Cleanable;
use Trismegiste\DokudokiBundle\Utils\InjectionClass;
use Trismegiste\DokudokiBundle\Transform\Mediator\Mediator;
use Trismegiste\DokudokiBundle\Transform\Mediator\AbstractMapper;

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
    public function mapFromDb($param)
    {
        $fqcn = $param[Mediator::FQCN_KEY];
        if (!class_exists($fqcn)) {
            throw new \DomainException("Cannot restore a '$fqcn' : class does not exist");
        }
        unset($param[Mediator::FQCN_KEY]);

        $reflector = new InjectionClass($fqcn);
        $obj = $reflector->newInstanceWithoutConstructor();

        foreach ($param as $key => $val) {
            // go deeper
            $mapped = $this->mediator->recursivCreate($val);
            // set the value
            $reflector->injectProperty($obj, $key, $mapped);
        }
        $reflector->fixHackBC($obj);
        // wakeup the object
        if ($obj instanceof Cleanable) {
            $obj->wakeup();
        }

        return $obj;
    }

    /**
     * {@inheritDoc}
     */
    public function mapToDb($obj)
    {
        if ($obj instanceof Skippable) {
            $dump = null;
        } else {
            if ($obj instanceof Cleanable) {
                $obj->sleep();
            }
            $reflector = new \ReflectionObject($obj);
            $dump = array();
            $dump[Mediator::FQCN_KEY] = $reflector->getName();
            foreach ($reflector->getProperties() as $prop) {
                if (!$prop->isStatic()) {
                    $prop->setAccessible(true);
                    // go deeper
                    $dump[$prop->name] = $this->mediator->recursivDesegregate($prop->getValue($obj));
                }
            }
        }

        return $dump;
    }

    /**
     * {@inheritDoc}
     */
    public function isResponsibleFromDb($var)
    {
        return (gettype($var) == 'array') && array_key_exists(Mediator::FQCN_KEY, $var);
    }

    /**
     * {@inheritDoc}
     */
    public function isResponsibleToDb($var)
    {
        return gettype($var) == 'object';
    }

}