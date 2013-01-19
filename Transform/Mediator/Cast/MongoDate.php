<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator\Cast;

use Trismegiste\DokudokiBundle\Transform\Mediator\AbstractMapper;

/**
 * MongoDate is a mapper to and from an \MongoDate
 *
 * @author florent
 */
class MongoDate extends AbstractMapper
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
    protected function getResponsibleType()
    {
        return array('MongoDate', 'DateTime');
    }

}