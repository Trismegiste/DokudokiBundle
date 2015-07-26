<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Migration\Analyser;

use Trismegiste\Yuurei\Transform\Mediator\AbstractMapper;

/**
 * BlackHole is responsible for everything and eats everything
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