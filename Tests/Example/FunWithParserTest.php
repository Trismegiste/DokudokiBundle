<?php

/*
 * DokudokiBundle
 */

namespace tests\Example;

use tests\Persistence\ConnectorTest;
use Trismegiste\DokudokiBundle\Facade\Provider;

/**
 * FunWithParser
 *
 * @author florent
 */
class FunWithParserTest extends \PHPUnit_Framework_TestCase
{

    protected $collection;
    protected $repo;

    protected function createBuilder()
    {
        return new \Trismegiste\DokudokiBundle\Transform\Delegation\Stage\Invocation();
    }

    protected function setUp()
    {
        $this->refl = new \ReflectionClass('tests\Fixtures\Branch');
        $test = new ConnectorTest();
        $this->collection = $test->testCollection();
        $facade = new Provider($this->collection);
        $this->repo = $facade->createRepository($this->createBuilder());
    }

    public function testInit()
    {
        $this->collection->drop();
    }

    public function testParsing()
    {

        $parser = new \PHPParser_Parser(new \PHPParser_Lexer());
        $code = file_get_contents($this->refl->getFileName());
        $record = new PhpFile($parser->parse($code));
        $this->repo->persist($record);

        return (string) $record->getId();
    }

    protected function stripWhiteSpace($str)
    {
        return preg_replace('#\s+#', ' ', $str);
    }

    /**
     * @depends testParsing
     */
    public function testCompiling($pk)
    {
        $ret = $this->repo->findByPk($pk);
        $code = file_get_contents($this->refl->getFileName());
        $this->assertEquals($this->stripWhiteSpace($code), $this->stripWhiteSpace('<?php ' . $ret));
    }

}