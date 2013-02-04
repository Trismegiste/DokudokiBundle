<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Transform\Delegation\Stage;

use Trismegiste\DokudokiBundle\Transform\Delegation\Stage\Invocation;

/**
 * test for Mediator created by Invocation builder
 *
 * @author flo
 */
class InvocationTest extends AbstractStageTest
{

    protected function createBuilder()
    {
        return new Invocation();
    }

}
