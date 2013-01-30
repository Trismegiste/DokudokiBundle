<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator\Colleague;

use Trismegiste\DokudokiBundle\Transform\Mediator\AbstractMapper;
use Trismegiste\DokudokiBundle\Transform\Mediator\TypeRegistry;
use Trismegiste\DokudokiBundle\Transform\Cleanable;
use Trismegiste\DokudokiBundle\Utils\InjectionClass;

/**
 * MapAlias is a mapper for FQCN alias
 *
 * @author florent
 */
class MapAlias extends AbstractMapper
{

    const CLASS_KEY = '-alias';

    protected $aliasMap;

    public function __construct(TypeRegistry $ctx, array $map)
    {
        parent::__construct($ctx);
        $this->aliasMap = $map;
    }

    public function isResponsibleFromDb($var)
    {
        return (gettype($var) == 'array')
                && array_key_exists(self::CLASS_KEY, $var)
                && array_key_exists($var[self::CLASS_KEY], $this->aliasMap);
    }

    public function isResponsibleToDb($var)
    {
        return (gettype($var) == 'object') && (FALSE !== array_search(get_class($var), $this->aliasMap));
    }

    public function mapFromDb($param)
    {
        $fqcn = $this->aliasMap[$param[self::CLASS_KEY]];
        unset($param[self::CLASS_KEY]);

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

    public function mapToDb($obj)
    {
        if ($obj instanceof Cleanable) {
            $obj->sleep();
        }
        $reflector = new \ReflectionObject($obj);
        $dump = array();
        $dump[self::CLASS_KEY] = array_search($reflector->getName(), $this->aliasMap);
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