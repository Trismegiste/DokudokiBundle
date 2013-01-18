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
class MapNullable implements Mapping
{

    public function mapFromDb($var)
    {

    }

    public function mapToDb($var)
    {
        return null;
    }

}