<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Transform\Delegation\Stage;

use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague;
use Trismegiste\Yuurei\Transform\Mediator\TypeRegistry;
use Trismegiste\Yuurei\Transform\Delegation\Stage\AbstractStage;

/**
 * Design Pattern : Builder
 * Component : Builder (concrete) 
 * 
 * A builder for mapping full of Magic.
 * Zero configuration needed : Quick and dirty.
 * Use case : Form driven development without model
 * CAUTION : Only for fast prototyping, that's why it's Black Magic
 *
 * @author flo
 */
class BlackMagic extends AbstractStage
{

    public function createObject(TypeRegistry $algo)
    {
        new Colleague\MapMagic($algo);
    }

}