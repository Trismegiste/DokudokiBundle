<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Transform\Mediator\Colleague;

/**
 * Description of MapperTestTemplate
 *
 * @author flo
 */
abstract class MapperTestTemplate extends \PHPUnit_Framework_TestCase
{

    protected $mapper;

    protected function setUp()
    {
        $this->mapper = $this->createMapper();
    }

    protected function tearDown()
    {
        unset($this->mapper);
    }

    abstract protected function createMapper();

    abstract public function getDataFromDb();

    abstract public function getDataToDb();

    /**
     * @dataProvider getDataFromDb
     */
    public function testMapFromDb($src, $dest)
    {
        $obj = $this->mapper->mapFromDb($src);
        $this->assertEquals($dest, $obj);
    }

    /**
     * @dataProvider getDataToDb
     */
    public function testMapToDb($src, $dest)
    {
        $dump = $this->mapper->mapToDb($src);
        $this->assertEquals($dest, $dump);
    }

}