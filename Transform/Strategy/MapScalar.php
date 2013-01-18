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