<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator\Colleague;

use Trismegiste\Alkahest\Transform\Mediator\TypeRegistry;
use Trismegiste\Alkahest\Transform\Mediator\Colleague\ObjectMapperTemplate;

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

    /**
     * {@inheritDoc}
     */
    public function isResponsibleFromDb($var)
    {
        return (gettype($var) == 'array')
                && array_key_exists(self::CLASS_KEY, $var)
                && array_key_exists($var[self::CLASS_KEY], $this->aliasMap);
    }

    /**
     * {@inheritDoc}
     */
    public function isResponsibleToDb($var)
    {
        return (gettype($var) == 'object') && (FALSE !== array_search(get_class($var), $this->aliasMap));
    }

    /**
     * {@inheritDoc}
     */
    protected function extractFqcn(array &$param)
    {
        $fqcn = $this->aliasMap[$param[self::CLASS_KEY]];
        unset($param[self::CLASS_KEY]);

        return $fqcn;
    }

    /**
     * {@inheritDoc}
     */
    protected function prepareDump(\ReflectionObject $reflector)
    {
        $dump = array();
        $dump[self::CLASS_KEY] = array_search($reflector->getName(), $this->aliasMap);

        return $dump;
    }

}