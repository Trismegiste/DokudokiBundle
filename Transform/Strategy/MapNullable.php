<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Strategy;

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