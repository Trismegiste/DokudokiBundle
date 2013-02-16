<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Migration;

use Trismegiste\DokudokiBundle\Migration\InvocationToWhiteMagic;
use Trismegiste\DokudokiBundle\Tests\Persistence\ConnectorTest;

/**
 * InvocationToWhiteMagicTest is ...
 *
 * @author flo
 */
class InvocationToWhiteMagicTest extends \PhpUnit_Framework_TestCase
{

    protected $migration;
    protected $collection;

    protected function setUp()
    {
        $test = new ConnectorTest();
        $this->collection = $test->testCollection();
        $this->migration = new InvocationToWhiteMagic($this->collection);
    }

    public function testScan()
    {
        $stat = $this->migration->analyse();
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

        $facade = new \Trismegiste\DokudokiBundle\Facade\Provider($this->collection);
        $src = $facade->createRepository(new \Trismegiste\DokudokiBundle\Transform\Delegation\Stage\Invocation());
        $dst = $facade->createRepository(new \Trismegiste\DokudokiBundle\Transform\Delegation\Stage\WhiteMagic($aliasMap));
        $this->migration->migrate($src, $dst);
    }

}