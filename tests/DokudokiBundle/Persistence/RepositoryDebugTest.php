<?php

/*
 * Dokudokibundle
 */

namespace tests\DokudokiBundle\Persistence;

use Trismegiste\DokudokiBundle\Persistence\RepositoryDebug;

/**
 * RepositoryDebugTest tests RepositoryDebug
 */
class RepositoryDebugTest extends \PHPUnit_Framework_TestCase
{

    protected $sut;
    protected $mockRepo;
    protected $logger;

    protected function setUp()
    {
        $this->mockRepo = $this->getMock('Trismegiste\Yuurei\Persistence\RepositoryInterface');
        $this->logger = $this->getMock('Trismegiste\DokudokiBundle\Persistence\Logger');
        $this->sut = new RepositoryDebug($this->mockRepo, $this->logger);
    }

    public function testLoggerFind()
    {
        $this->logger->expects($this->once())
                ->method('log')
                ->with($this->equalTo('find'), $this->anything(), $this->greaterThan(0));

        $this->sut->find();
    }

    public function testLoggerFindOne()
    {
        $this->logger->expects($this->once())
                ->method('log')
                ->with($this->equalTo('findOne'), $this->anything(), $this->greaterThan(0));

        $this->sut->findOne();
    }

    public function testLoggerFindByPk()
    {
        $this->logger->expects($this->once())
                ->method('log')
                ->with($this->equalTo('findByPk'), $this->anything(), $this->greaterThan(0));

        $this->sut->findByPk(123);
    }

    public function testLoggerGetCursor()
    {
        $this->logger->expects($this->once())
                ->method('log')
                ->with($this->equalTo('find'), $this->anything(), $this->greaterThan(0));

        $this->sut->getCursor();
    }

    public function testLoggerPersist()
    {
        $this->logger->expects($this->once())
                ->method('log')
                ->with($this->equalTo('create'), $this->anything(), $this->greaterThan(0));

        $this->sut->persist($this->getMock('Trismegiste\Yuurei\Persistence\Persistable'));
    }

    public function testLoggerBatchPersist()
    {
        $this->logger->expects($this->once())
                ->method('log')
                ->with($this->equalTo('batch'), $this->equalTo(['count' => 2]), $this->greaterThan(0));

        $doc = $this->getMock('Trismegiste\Yuurei\Persistence\Persistable');

        $this->sut->batchPersist([$doc, $doc]);
    }

}