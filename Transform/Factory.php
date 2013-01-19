<?php

/*
 * DokudokiBundle ◕ ‿‿﻿ ◕
 */

namespace Trismegiste\DokudokiBundle\Transform;

use Trismegiste\DokudokiBundle\Transform\Mediator;
use Trismegiste\DokudokiBundle\Transform\Mediator\MapObject;

/**
 * Factory is a transformer/factory to move from object to array and vice versa
 *
 * @author florent
 */
class Factory implements FactoryInterface
{

    protected $delegation;

    public function __construct()
    {
        $algo = new Mediator\Mediator();
        new Mediator\MapNullable($algo);
        new Mediator\MapScalar($algo);
        new Mediator\MapArray($algo);
        new Mediator\MapObject($algo);
        new Mediator\Cast\DateObject($algo);
        new Mediator\Cast\MongoBinData($algo);
        $this->delegation = $algo;
    }

    /**
     * {@inheritDoc}
     */
    public function desegregate($obj)
    {
        if (!is_object($obj)) {
            throw new \LogicException('Only object can be transformed into tree');
        }

        return $this->delegation->recursivDesegregate($obj);
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $dump)
    {
        if (!array_key_exists(MapObject::FQCN_KEY, $dump)) {
            throw new \LogicException('There is no key for the FQCN of the root entity');
        }

        return $this->delegation->recursivCreate($dump);
    }

}