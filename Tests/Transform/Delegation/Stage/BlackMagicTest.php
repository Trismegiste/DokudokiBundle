<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Transform\Delegation\Stage;

use Trismegiste\DokudokiBundle\Transform\Delegation\Stage\BlackMagic;

/**
 * test for Mediator created by BlackMagic builder
 *
 * @author flo
 */
class BlackMagicTest extends AbstractStageTest
{

    protected function createBuilder()
    {
        return new BlackMagic();
    }

}
