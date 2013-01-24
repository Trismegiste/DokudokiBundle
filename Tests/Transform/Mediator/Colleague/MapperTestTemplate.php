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

    abstract public function getResponsibleDataToDb();

    abstract public function getResponsibleDataFromDb();

    abstract public function getNotResponsibleDataToDb();

    abstract public function getNotResponsibleDataFromDb();

    /**
     * @dataProvider getResponsibleDataToDb
     */
    public function testResponsibleToDb($var)
    {
        $this->assertTrue($this->mapper->isResponsibleToDb($var));
    }

    /**
     * @dataProvider getResponsibleDataFromDb
     */
    public function testResponsibleFromDb($var)
    {
        $this->assertTrue($this->mapper->isResponsibleFromDb($var));
    }

    /**
     * @dataProvider getNotResponsibleDataToDb
     */
    public function testNotResponsibleToDb($var)
    {
        $this->assertFalse($this->mapper->isResponsibleToDb($var));
    }

    /**
     * @dataProvider getNotResponsibleDataFromDb
     */
    public function testNotResponsibleFromDb($var)
    {
        $this->assertFalse($this->mapper->isResponsibleFromDb($var));
    }

}