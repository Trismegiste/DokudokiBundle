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

    /**
     * Extract the FQCN from the array (coming from database)
     * Edge effect on the array
     * 
     * @param &array $param the array coming from db
     * 
     * @return string the FQCN extracted from the array
     */
    abstract protected function extractFqcn(array &$param);

    /**
     * get the array for dump to the database
     * 
     * @param \ReflectionObject $refl the reflection object of the object to dump
     * 
     * @return array a new initialized array
     */
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