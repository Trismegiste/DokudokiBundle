<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Persistence;

use Trismegiste\DokudokiBundle\Persistence\Repository;
use Trismegiste\DokudokiBundle\Facade\Provider;

/**
 * Test Template for repository
 *
 * @author flo
 */
abstract class RepositoryTestTemplate extends \PHPUnit_Framework_TestCase
{

    protected $collection;
    protected $repo;

    abstract protected function createBuilder();

    protected function setUp()
    {
        $test = new ConnectorTest();
        $this->collection = $test->testCollection();
        $facade = new Provider($this->collection);
        $this->repo = $facade->createRepository($this->createBuilder());
    }

    public function testInit()
    {
        $this->collection->drop();
    }

    /**
     * @expectedException \Trismegiste\DokudokiBundle\Persistence\NotFoundException
     */
    public function testNotFound()
    {
        $obj = $this->repo->findByPk(666);
    }

    abstract protected function getSimpleObject();

    /**
     *
     * @depends testInit
     */
    public function testCreation()
    {
        $simple = $this->getSimpleObject();
        $this->assertNull($simple->getId());
        $this->repo->persist($simple);
        $this->assertInstanceOf('\MongoId', $simple->getId());

        return (string) $simple->getId();
    }

    abstract protected function assertSimpleInsert(array $struc);

    /**
     * @depends testCreation
     */
    public function testInsert($pk)
    {
        $found = $this->collection->findOne(array('_id' => new \MongoId($pk)));
        $this->assertNotNull($found);
        $this->assertInternalType('array', $found);
        $this->assertSimpleInsert($found);

        return $pk;
    }

    /**
     * @depends testInsert
     */
    public function testRestore($pk)
    {
        $obj = $this->getSimpleObject();
        $obj->setId(new \MongoId($pk));
        $found = $this->repo->findByPk($pk);
        $this->assertEquals($obj, $found);

        return $obj;
    }

    abstract protected function editSimpleObject($obj);

    /**
     * @depends testRestore
     */
    public function testUpdate($obj)
    {
        $pk = $obj->getId();
        $this->editSimpleObject($obj);
        $this->repo->persist($obj);
        $this->assertEquals($pk, $obj->getId());

        return (string) $obj->getId();
    }

    abstract protected function assertEditedObject($obj);

    /**
     * @depends testUpdate
     */
    public function testUpdated($pk)
    {
        $obj = $this->repo->findByPk($pk);
        $this->assertEditedObject($obj);
    }

    abstract protected function getComplexObject();

    public function testCycle()
    {
        $obj = $this->getComplexObject();
        $this->assertNull($obj->getId());
        $this->repo->persist($obj);
        $found = $this->repo->findByPk($obj->getId());
        $this->assertEquals($obj, $found);
    }

    /**
     * @expectedException \DomainException
     * @expectedExceptionMessage stdClass is not Persistable
     */
    public function testNotRestoringNonPersistable()
    {
        $collection = $this->getMockBuilder('MongoCollection')
                ->disableOriginalConstructor()
                ->setMethods(array('findOne'))
                ->getMock();
        $collection->expects($this->once())
                ->method('findOne')
                ->will($this->returnValue(array()));

        $factory = $this->getMockBuilder('Trismegiste\DokudokiBundle\Transform\Transformer')
                ->disableOriginalConstructor()
                ->getMock();
        $factory->expects($this->once())
                ->method('create')
                ->will($this->returnValue(new \stdClass()));
        $repo = new Repository($collection, $factory);
        $repo->findByPk(123);
    }

}
