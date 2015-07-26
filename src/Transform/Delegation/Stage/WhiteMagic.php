<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Delegation\Stage;

use Trismegiste\Alkahest\Transform\Mediator\TypeRegistry;
use Trismegiste\Yuurei\Transform\Delegation\Stage\AbstractStage;
use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\MapAlias;

/**
 * Design Pattern : Builder
 * Component : Builder (concrete)
 *
 * A builder to automagically store object in database by using an alias map
 * for FQCN. You need to configure each class or it must implement Skippable
 *
 * Very strict : if a class does not exists in the alias map, it turns into
 * an array.
 *
 */
class WhiteMagic extends AbstractStage
{

    protected $aliasMap;

    public function __construct(array $map)
    {
        $this->aliasMap = $map;
    }

    public function createObject(TypeRegistry $algo)
    {
        new \Trismegiste\Alkahest\Transform\Mediator\Colleague\MapSkippable($algo);
        new MapAlias($algo, $this->aliasMap);
    }

    public function createBlackHole(TypeRegistry $algo)
    {
        parent::createBlackHole($algo);
        new \Trismegiste\Alkahest\Transform\Mediator\Colleague\MapFailure($algo);
    }

}
