<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Strategy;

/**
 * MapArray is a ...
 *
 * @author florent
 */
class MapArray implements Mapping
{

    public function mapFromDb($var)
    {

    }

    public function mapToDb($arr)
    {
        $dump = array();
        foreach ($arr as $key => $val) {
            // go depper
            $dump[$key] = $this->recursivDesegregate($val);
        }

        return $dump;
    }

}