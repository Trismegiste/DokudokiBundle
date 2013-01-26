<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator\Colleague;

use Trismegiste\DokudokiBundle\Transform\Mediator\AbstractMapper;

/**
 * MongoBinData is a mapper to and from an \MongoBinData .
 * Must be placed after MapObject
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
    public function isResponsibleFromDb($var)
    {
        return (gettype($var) == 'object' ) && (get_class($var) == 'MongoBinData');
    }

    /**
     * {@inheritDoc}
     */
    public function isResponsibleToDb($var)
    {
        return $this->isResponsibleFromDb($var);
    }

}