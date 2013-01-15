<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Persistence;

use Trismegiste\DokudokiBundle\Persistence\Connector;
use Trismegiste\DokudokiBundle\Transform\Factory;
use Trismegiste\DokudokiBundle\Persistence\Repository;

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
        $this->assertEquals(42, $obj->answer);
    }

}

class Simple implements \Trismegiste\DokudokiBundle\Persistence\Persistable
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