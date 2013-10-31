<?php

/*
 * Yuurei
 */

namespace Trismegiste\Yuurei\Transform\Mediator\Colleague;

use Trismegiste\Yuurei\Transform\Mediator\AbstractMapper;

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
    public function isResponsibleFromDb($var)
    {
        return gettype($var) == 'NULL';
    }

    /**
     * {@inheritDoc}
     */
    public function isResponsibleToDb($var)
    {
        return in_array(gettype($var), array('NULL', 'resource'));
    }

}