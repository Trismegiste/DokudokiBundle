<?php

/*
 * Yuurei
 */

namespace Trismegiste\Yuurei\Transform\Mediator\Colleague;

use Trismegiste\Yuurei\Transform\Mediator\AbstractMapper;

/**
 * MapScalar is a mapper to and from a scalar
 *
 * @author florent
 */
class MapScalar extends AbstractMapper
{

    protected $scalarType = array('boolean', 'integer', 'double', 'string');

    /**
     * {@inheritDoc}
     */
    public function mapFromDb($var)
    {
        return $var;
    }

    /**
     * {@inheritDoc}
     */
    public function mapToDb($var)
    {
        return $var;
    }

    /**
     * {@inheritDoc}
     */
    public function isResponsibleFromDb($var)
    {
        return in_array(gettype($var), $this->scalarType);
    }

    /**
     * {@inheritDoc}
     */
    public function isResponsibleToDb($var)
    {
        return $this->isResponsibleFromDb($var);
    }

}