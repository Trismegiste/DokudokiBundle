<?php

namespace Trismegiste\DokudokiBundle\Tests\Magic;

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
        $key = InternalContent::classKey;
        $this->doc = new InternalContent(array($key => 'hello', 'answer' => 42, 'recur' => new InternalContent(array($key => 'detail', 'data' => 123))));
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

    public function testOnlyClassKey()
    {
        $doc = new InternalContent('bye');
        $this->assertEquals('bye', $doc->getClassName());
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
        $this->assertContains(42, $this->doc->getIterator());
    }

}
