<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Migration\Analyser;

use Trismegiste\DokudokiBundle\Transform\Mediator\AbstractMapper;
use Trismegiste\DokudokiBundle\Transform\MappingException;

/**
 * MapFailure is the last mapper which throws exception when no other mapper
 * is responsible.
 *
 * @author florent
 */
class BlackHole extends AbstractMapper
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