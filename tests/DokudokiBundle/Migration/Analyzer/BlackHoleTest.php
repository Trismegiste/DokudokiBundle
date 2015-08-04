<?php

/*
 * DokudokiBundle
 */

namespace tests\Migration\Analyzer;

/**
 * BlackHoleTest code-covers the blackhole analazyer for Migration
 */
class BlackHoleTest extends \PhpUnit_Framework_TestCase
{

    protected $sut;

    protected function setUp()
    {
        $ctx = $this->getMock('Trismegiste\Alkahest\Transform\Mediator\TypeRegistry');
        $this->sut = new \Trismegiste\DokudokiBundle\Migration\Analyser\BlackHole($ctx);
    }

    public function testResponsible()
    {
        $this->sut->mapToDb('whatever');
        $this->assertTrue($this->sut->isResponsibleToDb('whatever'));
    }

}
