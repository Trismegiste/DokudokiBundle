<?php

/*
 * Yuurei
 */

namespace tests\Yuurei\Transform\Mediator\Colleague;

/**
 * Design pattern : Template method
 * MapperTestTemplate is a template for testing mappers
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

    protected function createMediatorMockup()
    {
        $mediator = $this->getMockForAbstractClass('Trismegiste\Yuurei\Transform\Mediator\AbstractMediator');
        $mediator->expects($this->any())
                ->method('recursivCreate')
                ->will($this->returnArgument(0));
        $mediator->expects($this->any())
                ->method('recursivDesegregate')
                ->will($this->returnArgument(0));

        return $mediator;
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