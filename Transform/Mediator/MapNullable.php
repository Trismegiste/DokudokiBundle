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

    }

    public function mapToDb($var)
    {
        return null;
    }

}