<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Mediator\Colleague;

use Trismegiste\DokudokiBundle\Transform\Mediator\AbstractMapper;
use Trismegiste\DokudokiBundle\Transform\Skippable;

/**
 * MapSkippable is a mapper responsible for implementation of Skippable
 *
 * @author florent
 */
class MapSkippable extends AbstractMapper
{

    public function isResponsibleFromDb($var)
    {
        return false;
    }

    public function isResponsibleToDb($var)
    {
        return ('object' == gettype($var)) && ($var instanceof Skippable);
    }

    public function mapFromDb($var)
    {
        throw new \LogicException('There is a bug here');
    }

    public function mapToDb($var)
    {
        return null;
    }

}