<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator\Colleague;

use Trismegiste\DokudokiBundle\Transform\Mediator\AbstractMapper;
use Trismegiste\DokudokiBundle\Transform\Cleanable;
use Trismegiste\DokudokiBundle\Utils\InjectionClass;

/**
 * Design Pattern : Template Method
 * 
 * ObjectMapperTemplate is a template for mapping object
 *
 * @author florent
 */
abstract class ObjectMapperTemplate extends AbstractMapper
{

    abstract protected function extractFqcn(array &$param);

    abstract protected function prepareDump(\ReflectionObject $refl);

    /**
     * {@inheritDoc}
     */
    public function mapFromDb($param)
    {
        $fqcn = $this->extractFqcn($param);

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
        if ($obj instanceof Cleanable) {
            $obj->sleep();
        }
        $reflector = new \ReflectionObject($obj);
        $dump = $this->prepareDump($reflector);
        foreach ($reflector->getProperties() as $prop) {
            if (!$prop->isStatic()) {
                $prop->setAccessible(true);
                // go deeper
                $dump[$prop->name] = $this->mediator->recursivDesegregate($prop->getValue($obj));
            }
        }

        return $dump;
    }

}