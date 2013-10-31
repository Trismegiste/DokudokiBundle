<?php

namespace tests\Magic;

use Trismegiste\DokudokiBundle\Magic\InternalContent;

/**
 * Test for Mother of Automagic
 *
 * @author flo
 */
class InternalContentTest extends \PHPUnit_Framework_TestCase
{

    private $doc;

    protected function setUp()
    {
        $this->doc = new InternalContent('hello');
    }

    protected function tearDown()
    {
        unset($this->doc);
    }

    public function testClassName()
    {
        $this->assertEquals('hello', $this->doc->getClassName());
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testNoClassName()
    {
        $doc = new InternalContent();
    }

    /**
     * @expectedException \LogicException
     */
    public function testNoClassTypeFail()
    {
        $doc = new InternalContent(array('app' => 'no'));
    }

    public function testIterator()
    {
        $this->assertInstanceOf('RecursiveArrayIterator', $this->doc->getIterator());
    }

}
