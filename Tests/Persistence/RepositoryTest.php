<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Persistence;

use Trismegiste\DokudokiBundle\Transform\Factory;
use Trismegiste\DokudokiBundle\Persistence\Repository;
use Trismegiste\DokudokiBundle\Persistence\Persistable;
use Trismegiste\DokudokiBundle\Magic\Document;

/**
 * Description of ConnectorTest
 *
 * @author flo
 */
class RepositoryTest extends \PHPUnit_Framework_TestCase
{

    protected $collection;
    protected $factory;
    protected $repo;

    protected function setUp()
    {
        $test = new ConnectorTest();
        $this->collection = $test->testCollection();
        $this->factory = new Factory();
        $this->repo = new Repository($this->collection, $this->factory);
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

    /**
     *
     * @depends testInit
     */
    public function testPersistence()
    {
        $simple = new Simple();
        $simple->answer = 42;
        $this->repo->persist($simple);
        $this->assertInstanceOf('\MongoId', $simple->getId());

        return (string) $simple->getId();
    }

    /**
     * @depends testPersistence
     */
    public function testRestore($pk)
    {
        $obj = $this->repo->findByPk($pk);
        $this->assertInstanceOf(__NAMESPACE__ . '\Simple', $obj);
        $this->assertEquals(42, $obj->answer);

        return $obj;
    }

    /**
     * @depends testRestore
     */
    public function testUpdate($obj)
    {
        $pk = $obj->getId();
        $obj->answer = 73;  // the best number as you know
        $this->repo->persist($obj);
        $this->assertEquals($pk, $obj->getId());

        return (string) $obj->getId();
    }

    /**
     * @depends testUpdate
     */
    public function testUpdated($pk)
    {
        $obj = $this->repo->findByPk($pk);
        $this->assertEquals(73, $obj->answer);
    }

    public function testFull()
    {
        $obj = new Stress();
        $this->repo->persist($obj);
        $found = $this->repo->findByPk($obj->getId());
        $this->assertEquals($obj, $found);
    }

    public function testPersistMagicDoc()
    {
        $obj = new Document('doku');
        $obj->setAnswer(42);
        $this->repo->persist($obj);
        $this->assertInstanceOf('\MongoId', $obj->getId());
        return (string) $obj->getId();
    }

    /**
     * @depends testPersistMagicDoc
     */
    public function testRestoreMagicDoc($pk)
    {
        $obj = $this->repo->findByPk($pk);
        $this->assertInstanceOf('Trismegiste\DokudokiBundle\Magic\Document', $obj);
        $this->assertEquals(42, $obj->getAnswer());
    }

    /**
     * @expectedException \DomainException
     * @expectedExceptionMessage stdClass is not Persistable
     */
    public function testNotRestoringNonPersistable()
    {
        $collection = $this->getMockBuilder('MongoCollection')
                ->disableOriginalConstructor()
                ->getMock();
        $collection->expects($this->once())
                ->method('findOne')
                ->will($this->returnValue(array()));

        $factory = $this->getMockBuilder('Trismegiste\DokudokiBundle\Transform\Factory')
                ->disableOriginalConstructor()
                ->getMock();
        $factory->expects($this->once())
                ->method('create')
                ->will($this->returnValue(new \stdClass()));
        $repo = new Repository($collection, $factory);
        $repo->findByPk(123);
    }

}

class Simple implements Persistable
{

    protected $id;

    public function setId(\MongoId $pk)
    {
        $this->id = $pk;
    }

    public function getId()
    {
        return $this->id;
    }

    public $answer;

}

class Stress extends Simple
{

    // silly names to track bug
    protected $floatVar;
    protected $binaryVar;
    protected $dateVar;
    protected $stringVar;
    protected $intVar;
    protected $objVar;
    static public $iDontLikeStatic = "dark matter";

    public function __construct()
    {
        $this->answer = 42;
        $this->binaryVar = new \MongoBinData("299792458", 2);
        $this->floatVar = 3.14159265; // don't know after that
        $this->dateVar = new \DateTime();
        $this->intVar = 73; // the best number
        $this->stringVar = 'H Psi = E . Psi';
        $this->objVar = new Simple();
        $this->objVar->answer = 'eureka';
        $this->magic = new Document('person');
        $this->magic->setName('Howard');
    }

}