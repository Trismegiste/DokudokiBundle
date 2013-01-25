<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator\Colleague;

use Trismegiste\DokudokiBundle\Transform\Mediator\AbstractMapper;
use Trismegiste\DokudokiBundle\Transform\Mediator\Mediator;

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
     * {@inheritDoc}
     */
    public function mapFromDb($param)
    {
        return array_map(array($this->mediator, 'recursivCreate'), $param);
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
    public function isResponsibleFromDb($var)
    {
        return 'array' == gettype($var);
    }

    /**
     * {@inheritDoc}
     */
    public function isResponsibleToDb($var)
    {
        return 'array' == gettype($var);
    }

}