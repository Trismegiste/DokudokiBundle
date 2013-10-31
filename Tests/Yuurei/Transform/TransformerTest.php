<?php

/*
 * Dokudoki
 */

namespace Trismegiste\Yuurei\Transform\Tests;

use Trismegiste\Yuurei\Transform\Transformer;
use Trismegiste\Yuurei\Transform\Delegation\MappingDirector;
use Trismegiste\Yuurei\Transform\Delegation\Stage\Invocation;
use tests\Yuurei\Fixtures\IntoVoid;

/**
 * TransformerTest test for Transformer
 *
 * @author florent
 */
class TransformerTest extends \PHPUnit_Framework_TestCase
{

    protected $service;

    protected function setUp()
    {
        $director = new MappingDirector();
        $this->service = new Transformer($director->create(new Invocation()));
    }

    protected function tearDown()
    {
        unset($this->service);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testOnlyObject()
    {
        $dump = $this->service->desegregate(array('nawak'));
    }

    /**
     * @expectedException \LogicException
     */
    public function testSkippable()
    {
        $obj = new IntoVoid();
        $dump = $this->service->desegregate($obj);
    }

    /**
     * The tranformer MUST return an object
     *
     * @expectedException \RuntimeException
     */
    public function testExceptionForBadCreation()
    {
        $this->service->create(array('bazinga' => 73));
    }

}
