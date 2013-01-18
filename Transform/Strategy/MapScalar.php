<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Strategy;

/**
 * Scalar is a ...
 *
 * @author florent
 */
class MapScalar implements Mapping
{

    public function mapFromDb($var)
    {

    }

    public function mapToDb($var)
    {
        return $var;
    }

}