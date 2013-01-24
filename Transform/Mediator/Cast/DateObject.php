<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator\Cast;

use Trismegiste\DokudokiBundle\Transform\Mediator\AbstractMapper;

/**
 * DateObject is a transcaster \MongoDate <=> DateTime
 *
 * @author florent
 */
class DateObject extends AbstractMapper
{

    /**
     * {@inheritDoc}
     */
    public function mapFromDb($var)
    {
        $ret = new \DateTime();
        $ret->setTimestamp($var->sec);
        return $ret;
    }

    /**
     * {@inheritDoc}
     */
    public function mapToDb($obj)
    {
        if (get_class($obj) == 'MongoDate') {
            throw new \LogicException('Cannot transform MongoDate because reversed will be a DateTime');
        }
        return new \MongoDate($obj->getTimestamp());
    }

    /**
     * {@inheritDoc}
     */
    public function isResponsibleFromDb($var)
    {
        return (gettype($var) == 'object' ) && (get_class($var) == 'MongoDate');
    }

    /**
     * {@inheritDoc}
     */
    public function isResponsibleToDb($var)
    {
        return (gettype($var) == 'object' ) && (get_class($var) == 'DateTime');
    }

}