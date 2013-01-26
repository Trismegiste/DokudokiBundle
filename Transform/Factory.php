<?php

/*
 * DokudokiBundle ◕ ‿‿ ◕
 */

namespace Trismegiste\DokudokiBundle\Transform;

use Trismegiste\DokudokiBundle\Transform\Mediator;
use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague;

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
        new Colleague\MapNullable($algo);
        new Colleague\MapScalar($algo);
        new Colleague\MapArray($algo);
        new Colleague\MapObject($algo);
        new Colleague\MapSkippable($algo);
        new Colleague\MapMagic($algo);
        new Colleague\DateObject($algo);
        new Colleague\MongoBinData($algo);
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
        if ($obj instanceof Skippable) {
            throw new \LogicException('A root entity cannot be Skippable');
        }

        return $this->delegation->recursivDesegregate($obj);
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $dump)
    {
        $obj = $this->delegation->recursivCreate($dump);
        if (gettype($obj) != 'object') {
            throw new \LogicException('There is no key for the FQCN of the root entity');
        }

        return $obj;
    }

}