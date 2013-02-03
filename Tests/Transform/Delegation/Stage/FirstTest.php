<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Transform\Delegation\Stage;

use Trismegiste\DokudokiBundle\Transform\Delegation\Stage\First;

/**
 * test for Mediator created by First builder
 *
 * @author flo
 */
class FirstTest extends AbstractStageTest
{

    protected function createBuilder()
    {
        return new First();
    }

}
