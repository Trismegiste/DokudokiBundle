<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator\Cast;

use Trismegiste\DokudokiBundle\Transform\Mediator\AbstractMapper;

/**
 * MongoBinData is a mapper to and from an \MongoBinData
 *
 * @author florent
 */
class MongoBinData extends AbstractMapper
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
    public function mapToDb($obj)
    {
        return $obj;
    }

    /**
     * {@inheritDoc}
     */
    protected function getResponsibleType()
    {
        return array('MongoBinData');
    }

}