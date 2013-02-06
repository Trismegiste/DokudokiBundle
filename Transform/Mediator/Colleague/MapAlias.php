<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator\Colleague;

use Trismegiste\DokudokiBundle\Transform\Mediator\TypeRegistry;

/**
 * MapAlias is a mapper for FQCN alias
 *
 * @author florent
 */
class MapAlias extends ObjectMapperTemplate
{
    const CLASS_KEY = '-class';

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

    protected function extractFqcn(array &$param)
    {
        $fqcn = $this->aliasMap[$param[self::CLASS_KEY]];
        unset($param[self::CLASS_KEY]);

        return $fqcn;
    }

    protected function prepareDump(\ReflectionObject $reflector)
    {
        $dump = array();
        $dump[self::CLASS_KEY] = array_search($reflector->getName(), $this->aliasMap);

        return $dump;
    }

}