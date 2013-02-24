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
        $this->migration = new BlackToWhiteMagic($this->collection, array('trunk' => 'Alias\Trunk'));
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

        $this->assertEquals(array(
            'found' => array(
                'trunk' => 1,
                'branch' => (1 << $this->depth) - 2,
                'leaf' => 1 << $this->depth
            ),
            'properties' => array(
                'trunk' => array(
                    '_id' => true,
                    'left' => true,
                    'right' => true,
                ),
                'branch' => array(
                    'left' => true,
                    'right' => true,
                )
            ),
            'root' => 1
                ), $stat);

        return $stat;
    }

    public function testFilterOff()
    {
        $stat = $this->migration->filter();
        $this->assertEquals(array(
            'alias' => array(
                'trunk' => array(
                    'fqcn' => 'Alias\Trunk',
                    'properties' => array(
                        0 => '_id',
                        1 => 'left',
                        2 => 'right',
                    )
                ),
                'branch' => array(
                    'fqcn' => 'Not\Found\FQCN\Branch',
                    'properties' => array(
                        0 => 'left',
                        1 => 'right',
                    )
                ),
                'leaf' => array(
                    'fqcn' => 'Not\Found\FQCN\Leaf',
                )
            )
                ), $stat);
    }

    public function testFilterOn()
    {
        $stat = $this->migration->filter(true);
        $this->assertEquals(array(
            'alias' => array(
                'branch' => array(
                    'fqcn' => 'Not\Found\FQCN\Branch',
                    'properties' => array(
                        0 => 'left',
                        1 => 'right',
                    )
                ),
                'leaf' => array(
                    'fqcn' => 'Not\Found\FQCN\Leaf',
                )
            )
                ), $stat);
    }

    /**
     * @depends testScan
     */
    public function testGenerate($stat)
    {
        $cfg = \Symfony\Component\Yaml\Yaml::parse(file_get_contents(__DIR__ . '/model1.yml'));
        $classArray = $this->migration->generate($cfg['alias']);
        $this->assertCount(3, $classArray);
        foreach ($classArray as $idx => $generated) {
            file_put_contents(sys_get_temp_dir() . "/tmp_$idx.php", $generated);
        }
    }

}