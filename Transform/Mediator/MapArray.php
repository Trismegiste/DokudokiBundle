<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator;

/**
 * MapArray is a ...
 *
 * @author florent
 */
class MapArray extends AbstractMapper
{

    public function mapFromDb($var)
    {

    }

    public function mapToDb($arr)
    {
        $dump = array();
        foreach ($arr as $key => $val) {
            // go depper
            $dump[$key] = $this->mediator->recursivDesegregate($val);
        }

        return $dump;
    }

}