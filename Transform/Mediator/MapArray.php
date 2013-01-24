<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator;

/**
 * Design Pattern : Mediator
 * Component : Colleague (concrete)
 *
 * MapArray deals the mapping with arrays
 *
 * @author florent
 */
class MapArray extends AbstractMapper
{

    /**
     * {@inheritDoc}
     */
    public function mapFromDb($param)
    {
        return array_map(array($this->mediator, 'recursivCreate'), $param);
    }

    /**
     * {@inheritDoc}
     */
    public function mapToDb($arr)
    {
        return array_map(array($this->mediator, 'recursivDesegregate'), $arr);
    }

    /**
     * {@inheritDoc}
     */
    public function isResponsibleFromDb($var)
    {
        $internal = gettype($var);
        if ($internal == 'array') {
            return !array_key_exists(Mediator::FQCN_KEY, $var);
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function isResponsibleToDb($var)
    {
        return 'array' == gettype($var);
    }

}