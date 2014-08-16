<?php

/*
 * Dokudokibundle
 */

namespace tests\DokudokiBundle;

use Trismegiste\DokudokiBundle\Persistence\DataCollector;

/**
 * DataCollectorTest tests DataCollector
 */
class DataCollectorTest extends \PHPUnit_Framework_TestCase
{

    protected $sut;

    protected function setUp()
    {
        $this->sut = new DataCollector();
    }

    public function testLog()
    {
        $this->assertEquals('mongodb', $this->sut->getName());
        $this->sut->log('read', ['info'], 3);
        $this->assertCount(1, $this->sut->getQueries());
        $this->assertEquals(1, $this->sut->getQueriesCount());
    }

}