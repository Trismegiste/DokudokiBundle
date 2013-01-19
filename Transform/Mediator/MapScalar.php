<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator;

/**
 * MapScalar is a ...
 *
 * @author florent
 */
class MapScalar extends AbstractMapper
{

    public function mapFromDb($var)
    {

    }

    public function mapToDb($var)
    {
        return $var;
    }

}