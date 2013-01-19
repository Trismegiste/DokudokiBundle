<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Persistence;

use Trismegiste\DokudokiBundle\Persistence\Connector;
use Trismegiste\DokudokiBundle\Transform\Factory;
use Trismegiste\DokudokiBundle\Persistence\Repository;
use Trismegiste\DokudokiBundle\Persistence\Persistable;

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
        $this->binaryVar = new \MongoBinData("299792458", 2);
        $this->floatVar = 3.14159265; // don't know after that
        $this->dateVar = new \DateTime();
        $this->intVar = 73; // the best number
        $this->stringVar = "H Psi = E . Psi";
        $this->objVar = new Simple();
    }

}