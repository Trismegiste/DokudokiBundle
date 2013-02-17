<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Migration;

use Trismegiste\DokudokiBundle\Migration\BlackToWhiteMagic;
use Trismegiste\DokudokiBundle\Tests\Persistence\ConnectorTest;
use Trismegiste\DokudokiBundle\Facade\Provider;
use Trismegiste\DokudokiBundle\Transform\Delegation\Stage;
use Trismegiste\DokudokiBundle\Magic\Document;

/**
 * BlackToWhiteMagicTest is ...
 *
 * @author flo
 */
class BlackToWhiteMagicTest extends \PhpUnit_Framework_TestCase
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
        $this->migration = new BlackToWhiteMagic($this->collection);
        $this->facade = new Provider($this->collection);
        $this->source = $this->facade->createRepository(new Stage\BlackMagic());
        $this->stopWatch = microtime(true);
    }

    protected function tearDown()
    {
        $delta = microtime(true) - $this->stopWatch;
//        printf("\n%.0f ms\n", 1000 * $delta);
    }

    protected function createTree(Document $node, $lvl)
    {
        if ($lvl == 0) {
            return new Document('leaf');
        } else {
            $node->setLeft($this->createTree(new Document('branch'), $lvl - 1));
            $node->setRight($this->createTree(new Document('branch'), $lvl - 1));
            return $node;
        }
    }

    public function testInit()
    {
        $this->collection->drop();
        $tree = $this->createTree(new Document('trunk'), $this->depth);
        $this->source->persist($tree);
    }

    public function testScan()
    {
        $stat = $this->migration->analyse();
//        print_r($stat);
        $this->assertEquals(array(
            'found' => array(
                'trunk' => 1,
                'branch' => (1 << $this->depth) - 2,
                'leaf' => 1 << $this->depth
            ),
            'root' => 1
                ), $stat);

        return $stat;
    }

    /**
     * @depends testScan
     */
    public function testGenerate($stat)
    {
        $this->migration->generate($stat, $aliasCfg);
    }

}