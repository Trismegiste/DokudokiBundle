<?php

namespace Trismegiste\DokudokiBundle\Tests\Magic;

use Trismegiste\DokudokiBundle\Magic\Document;

/**
 * Test for Document
 *
 * @author flo
 */
class DocumentTest extends \PHPUnit_Framework_TestCase
{

    private $doc;

    protected function setUp()
    {
        $this->doc = new Document(array(Document::classKey => 'hello', 'answer' => 42));
    }

    protected function tearDown()
    {
        unset($this->doc);
    }

    public function testClassName()
    {
        $this->assertEquals('hello', $this->doc->getClassName());
    }

    public function testGetter()
    {
        $this->assertEquals(42, $this->doc->getAnswer());
    }

    public function testKnownSetter()
    {
        $this->doc->setAnswer(299792458);
        $this->assertEquals(299792458, $this->doc->getAnswer());
    }

    public function testUnknownSetter()
    {
        $this->doc->setBeast(666);
        $this->assertEquals(666, $this->doc->getBeast());
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Method azrfsfd is unknown
     */
    public function testUnknownMethod()
    {
        $this->doc->azrfsfd();
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Method setazrfsfd is unknown
     */
    public function testAlmostKnownMethod()
    {
        $this->doc->setazrfsfd(2);
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Method setOld_property is unknown
     */
    public function testUnderscoreIsOldSchool()
    {
        $this->doc->setOld_property(2);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidArgumentGetter()
    {
        $this->doc->getAnswer(3);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidArgumentSetter2()
    {
        $this->doc->setAnswer(3, 4);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidArgumentSetter0()
    {
        $this->doc->setAnswer();
    }

    /**
     * @expectedException \DomainException
     * @expectedExceptionMessage Property snark is unknown
     */
    public function testGetInvalidProp()
    {
        $this->doc->getSnark();
    }

    /**
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testNoClassName()
    {
        $doc = new Document();
    }

    public function testDefaultSubClassName()
    {
        $doc = new SubDocument();
        $this->assertEquals('subdoc', $doc->getClassName());
    }

    public function testOveriddenProperty()
    {
        $doc = new SubDocument(array('overridden' => 111));
        $this->assertEquals(222, $doc->getOverridden());
    }

    public function testFlatten()
    {
        $provider = new Fixture();
        $this->assertEquals($provider->getFullTreeFlat(), $provider->getFullTreeObject()->getUnTyped());
    }

}

/**
 * Some classes for testing
 */
class SubDocument extends Document
{

    protected $transientProp = 'nothing';

    public function getOverridden()
    {
        return parent::getOverridden() << 1;
    }

    public function __construct(array $data = array())
    {
        $data[Document::classKey] = 'subdoc';
        parent::__construct($data);
    }

}