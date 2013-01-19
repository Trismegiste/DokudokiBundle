<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator;

/**
 * Nullable is a ...
 *
 * @author florent
 */
class MapNullable extends AbstractMapper
{

    public function mapFromDb($var)
    {
        return null;
    }

    public function mapToDb($var)
    {
        return null;
    }

    protected function getResponsibleType()
    {
        return array('NULL', 'resource');
    }

}