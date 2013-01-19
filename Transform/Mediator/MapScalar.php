<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator;

/**
 * MapScalar is a mapper to and from a scalar
 *
 * @author florent
 */
class MapScalar extends AbstractMapper
{

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
    protected function getResponsibleType()
    {
        return array('boolean', 'integer', 'double', 'string');
    }

}