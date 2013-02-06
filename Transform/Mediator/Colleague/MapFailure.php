<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator\Colleague;

use Trismegiste\DokudokiBundle\Transform\Mediator\AbstractMapper;

/**
 * MapFailure is hte last mapper which throws exception when no other mapper
 * is responsible.
 *
 * @author florent
 */
class MapFailure extends AbstractMapper
{

    /**
     * {@inheritDoc}
     */
    public function mapFromDb($var)
    {
        throw new \RuntimeException('Non instantiable object in database');
    }

    /**
     * {@inheritDoc}
     */
    public function mapToDb($var)
    {
        throw new \InvalidArgumentException('Cannot dump object to database');
    }

    /**
     * {@inheritDoc}
     */
    public function isResponsibleFromDb($var)
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function isResponsibleToDb($var)
    {
        return true;
    }

}