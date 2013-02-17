<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Migration;

use Trismegiste\DokudokiBundle\Migration\InvocationToWhiteMagic;
use Trismegiste\DokudokiBundle\Tests\Persistence\ConnectorTest;
use Trismegiste\DokudokiBundle\Facade\Provider;
use Trismegiste\DokudokiBundle\Transform\Delegation\Stage;
use Trismegiste\DokudokiBundle\Tests\Fixtures;

/**
 * InvocationToWhiteMagicTest is ...
 *
 * @author flo
 */
class InvocationToWhiteMagicTest extends \PhpUnit_Framework_TestCase
{

    protected $migration;
    protected $collection;
    protected $source;
    protected $facade;
    protected $stopWatch;
    protected $depth = 6;

    protected function setUp()
    {
        $test = new ConnectorTest();
        $this->collection = $test->testCollection();
        $this->migration = new InvocationToWhiteMagic($this->collection);
        $this->facade = new Provider($this->collection);
        $this->source = $this->facade->createRepository(new Stage\Invocation());
        $this->stopWatch = microtime(true);
    }

    protected function tearDown()
    {
        $delta = microtime(true) - $this->stopWatch;
//        printf("\n%.0f ms\n", 1000 * $delta);
    }

    protected function createTree(Fixtures\Branch $node, $lvl)
    {
        if ($lvl == 0) {
            return new Fixtures\Leaf();
        } else {
            $node->append($this->createTree(new Fixtures\Branch(), $lvl - 1));
            $node->append($this->createTree(new Fixtures\Branch(), $lvl - 1));
            return $node;
        }
    }

    public function testInit()
    {
        $this->collection->drop();
        $tree = $this->createTree(new Fixtures\Trunk(), $this->depth);
        $this->source->persist($tree);
    }

    public function testScan()
    {
        $stat = $this->migration->analyse();

        $this->assertEquals(array(
            'missing' => array(),
            'fqcn' => array(
                'Trismegiste\\DokudokiBundle\\Tests\\Fixtures\\Trunk' => 1,
                'Trismegiste\\DokudokiBundle\\Tests\\Fixtures\\Branch' => (1 << $this->depth) - 2,
                'Trismegiste\\DokudokiBundle\\Tests\\Fixtures\\Leaf' => 1 << $this->depth
            ),
            'root' => 1
                ), $stat);

        return $stat;
    }

    /**
     * @depends testScan
     */
    public function testMigrate($stat)
    {
        foreach ($stat['fqcn'] as $className => $dummy) {
            $part = explode('\\', $className);
            $last = array_pop($part);
            $aliasMap['AliasFor' . $last] = $className;
        }

        $destination = $this->facade->createRepository(new Stage\WhiteMagic($aliasMap));
        $this->migration->migrate($this->source, $destination);
    }

}