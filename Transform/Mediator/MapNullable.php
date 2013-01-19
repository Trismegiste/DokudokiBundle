<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator;

/**
 * Nullable is a mapper to and from a nullable : null and resource
 *
 * @author florent
 */
class MapNullable extends AbstractMapper
{

    /**
     * {@inheritDoc}
     */
    public function mapFromDb($var)
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function mapToDb($var)
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    protected function getResponsibleType()
    {
        return array('NULL', 'resource');
    }

}