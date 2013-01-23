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
    protected function getResponsibleFromDb()
    {
        return array('array');
    }

    /**
     * {@inheritDoc}
     */
    protected function getResponsibleToDb()
    {
        return array('array');
    }

}