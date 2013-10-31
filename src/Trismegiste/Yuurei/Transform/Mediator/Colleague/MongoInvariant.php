<?php

/*
 * Yuurei
 */

namespace Trismegiste\Yuurei\Transform\Mediator\Colleague;

use Trismegiste\Yuurei\Transform\Mediator\AbstractMapper;

/**
 * MongoInvariant is a mapper to and from Mongo type which does not change
 * between memory and database, like MongoBinData or MongoId.
 * Must be responsible before MapObject to shortcut when in mapToDb
 * 
 * @author florent
 */
class MongoInvariant extends AbstractMapper
{

    /**
     * {@inheritDoc}
     */
    public function mapFromDb($var)
    {
        return $var;
    }

    /**
     * {@inheritDoc}
     */
    public function mapToDb($obj)
    {
        return $obj;
    }

    /**
     * {@inheritDoc}
     */
    public function isResponsibleFromDb($var)
    {
        return (gettype($var) == 'object' )
                && in_array(get_class($var), array('MongoBinData', 'MongoId'));
    }

    /**
     * {@inheritDoc}
     */
    public function isResponsibleToDb($var)
    {
        return $this->isResponsibleFromDb($var);
    }

}