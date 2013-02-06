<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Delegation\Stage;

use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague;
use Trismegiste\DokudokiBundle\Transform\Mediator\TypeRegistry;

/**
 * Design Pattern : Builder
 * Component : Builder (concrete)
 *
 * A builder which mix WhiteMagic and BlackMagic.
 * You need to configure each class or it must implement Skippable
 *
 * Very strict : if a class does not exists in the alias map, it turns into
 * an array. You can also mix your model with Magic\Document but there is not
 * automatic translation from/to magic doc is a class is not alias
 * This is for sanity : you'll restore the same type you had persisted
 *
 */
class Hoodoo extends AbstractStage
{

    protected $aliasMap;

    public function __construct(array $map)
    {
        $this->aliasMap = $map;
    }

    public function createObject(TypeRegistry $algo)
    {
        new Colleague\MapMagic($algo);
        new Colleague\MapSkippable($algo);
        new Colleague\MapAlias($algo, $this->aliasMap);
    }

    public function createBlackHole(TypeRegistry $algo)
    {
        parent::createBlackHole($algo);
        new Colleague\MapFailure($algo);
    }

}