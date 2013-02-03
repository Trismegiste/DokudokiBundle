<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Transform\Delegation\Stage;

use Trismegiste\DokudokiBundle\Transform\Delegation\MappingDirector;

/**
 * Template test for Mediator created by a builder
 *
 * @author flo
 */
abstract class AbstractStageTest extends \PHPUnit_Framework_TestCase
{

    protected $mediator;

    protected function setUp()
    {
        $director = new MappingDirector();
        $bluePrint = $this->createBuilder();
        $this->mediator = $director->create($bluePrint);
    }

    protected function tearDown()
    {
        unset($this->mediator);
    }

    abstract protected function createBuilder();
}
