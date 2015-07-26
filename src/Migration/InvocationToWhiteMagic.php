<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Migration;

use Trismegiste\Yuurei\Transform\Mediator\Colleague\MapArray;
use Trismegiste\DokudokiBundle\Migration\Analyser;
use Trismegiste\Yuurei\Transform\Mediator\Mediator;

/**
 * InvocationToWhiteMagic is a service for migrating from Invocation stages
 * to WhiteMagic stage
 */
class InvocationToWhiteMagic extends StageMigration
{

    protected $classStat;

    protected function buildMapper(Mediator $algo)
    {
        $catcher = new Analyser\FqcnCollector($algo);
        $this->classStat = &$catcher->collector;
        new MapArray($algo);
        new Analyser\BlackHole($algo);
    }

    public function analyse()
    {
        $cardinal = parent::analyse();
        $this->classStat['root'] = $cardinal;

        return $this->classStat;
    }

}