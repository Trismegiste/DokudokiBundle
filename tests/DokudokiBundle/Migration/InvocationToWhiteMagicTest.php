<?php

/*
 * Dokudokibundle
 */

namespace tests\Migration;

use Trismegiste\DokudokiBundle\Migration\InvocationToWhiteMagic;
use tests\Yuurei\Persistence\ConnectorTest;
use Trismegiste\Yuurei\Facade\Provider;
use Trismegiste\DokudokiBundle\Transform\Delegation\Stage\WhiteMagic;
use Trismegiste\Yuurei\Transform\Delegation\Stage\Invocation;
use tests\Yuurei\Fixtures;

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
        $this->source = $this->facade->createRepository(new Invocation());
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
            $node->setLeft($this->createTree(new Fixtures\Branch(), $lvl - 1));
            $node->setRight($this->createTree(new Fixtures\Branch(), $lvl - 1));
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
                'tests\Yuurei\Fixtures\Trunk' => 1,
                'tests\Yuurei\Fixtures\Branch' => (1 << $this->depth) - 2,
                'tests\Yuurei\Fixtures\Leaf' => 1 << $this->depth
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

        $destination = $this->facade->createRepository(new WhiteMagic($aliasMap));
        $migrated = $this->migration->migrate($this->source, $destination);
        $this->assertEquals(1, $migrated);
        
        $struc = $this->collection->findOne([]);
        $this->assertEquals('AliasForTrunk', $struc['-class']);
        $this->assertEquals('AliasForBranch', $struc['left']['-class']);
        $this->assertEquals('AliasForBranch', $struc['right']['-class']);
    }

}