<?php

/*
 * Yuurei
 */

namespace Trismegiste\Yuurei\Transform\Mediator\Colleague;

use Trismegiste\Yuurei\Transform\Mediator\AbstractMapper;
use Trismegiste\Yuurei\Transform\Skippable;

/**
 * MapSkippable is a mapper responsible for implementations of Skippable.
 * It overrides MapObject if following it
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