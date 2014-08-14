<?php

/*
 * DokudokiBundle
 */

namespace tests\DokudokiBundle\Persistence;

/**
 * Template for testing repository decorators
 */
abstract class DecoratorTestTemplate extends \PHPUnit_Framework_TestCase
{

    protected $sut;
    protected $mockRepo;
    protected $filter;

    protected function setUp()
    {
        $this->mockRepo = $this->getMock('Trismegiste\Yuurei\Persistence\RepositoryInterface');
        $this->sut = $this->createRepository();
        $this->filter = $this->getFilter();
    }

    abstract protected function createRepository();

    abstract protected function getFilter();

    public function testFindWithFilter()
    {
        $this->mockRepo->expects($this->once())
                ->method('find')
                ->with($this->equalTo($this->filter));

        $this->sut->find();
    }

    public function testFindOneWithFilter()
    {
        $this->mockRepo->expects($this->once())
                ->method('findOne')
                ->with($this->equalTo($this->filter));

        $this->sut->findOne();
    }

    public function testGetCursorWithFilter()
    {
        $this->mockRepo->expects($this->once())
                ->method('getCursor')
                ->with($this->equalTo($this->filter));

        $this->sut->getCursor();
    }

}