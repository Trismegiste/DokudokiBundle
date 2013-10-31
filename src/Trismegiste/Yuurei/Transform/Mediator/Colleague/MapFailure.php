<?php

/*
 * Yuurei
 */

namespace Trismegiste\Yuurei\Transform\Mediator\Colleague;

use Trismegiste\Yuurei\Transform\Mediator\AbstractMapper;
use Trismegiste\Yuurei\Transform\MappingException;

/**
 * MapFailure is the last mapper which throws exception when no other mapper
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
        throw new MappingException($var, 'restoration');
    }

    /**
     * {@inheritDoc}
     */
    public function mapToDb($var)
    {
        throw new MappingException($var, 'persistence');
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