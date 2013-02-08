<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Transform\Mediator\Colleague;

use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\MapFailure;

/**
 * Design pattern : Template method
 * MapFailureTest tests for MapFailure (catches mapping problems)
 */
class MapFailureTest extends \PHPUnit_Framework_TestCase
{

    protected $mapper;

    protected function setUp()
    {
        $mediator = $this->getMockForAbstractClass('Trismegiste\DokudokiBundle\Transform\Mediator\AbstractMediator');
        $this->mapper = new MapFailure($mediator);
    }

    protected function tearDown()
    {
        unset($this->mapper);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testMapFromDb()
    {
        $obj = $this->mapper->mapFromDb(123);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMapToDb()
    {
        $dump = $this->mapper->mapToDb(123);
    }

    public function testResponsibleToDb()
    {
        $this->assertTrue($this->mapper->isResponsibleToDb(123));
    }

    public function testResponsibleFromDb()
    {
        $this->assertTrue($this->mapper->isResponsibleFromDb(123));
    }

}