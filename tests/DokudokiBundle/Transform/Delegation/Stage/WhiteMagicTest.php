<?php

/*
 * DokudokiBundle
 */

namespace tests\DokudokiBundle\Transform\Delegation\Stage;

use Trismegiste\DokudokiBundle\Transform\Delegation\Stage\WhiteMagic;
use tests\Yuurei\Fixtures;
use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\MapAlias;
use tests\Yuurei\Transform\Delegation\Stage\AbstractStageTest;

/**
 * test for Mediator created by WhiteMagic builder
 */
class WhiteMagicTest extends AbstractStageTest
{

    protected function createBuilder()
    {
        $fixture = 'tests\Yuurei\Fixtures';
        $alias = array(
            'default' => 'stdClass',
            'Cart' => $fixture . '\Cart',
            'Product' => $fixture . '\Product',
            'NonTrivial' => $fixture . '\VerifMethod',
            'Hibernate' => $fixture . '\Bear',
        );
        return new WhiteMagic($alias);
    }

    public function getSampleTree()
    {
        $obj = new \stdClass();
        $obj->bestNumber = 73;
        $dump = array(MapAlias::CLASS_KEY => 'default', 'bestNumber' => 73);

        $obj2 = new Fixtures\Cart("Bradburry appartments");
        $obj2->info = 'confound these ponies';
        $obj2->addItem(3, new Fixtures\Product('5D mk III', 2999));
        $obj2->addItem(1, new Fixtures\Product('VTOL', 6500));

        $dump2 = array(
            MapAlias::CLASS_KEY => 'Cart',
            'address' => 'Bradburry appartments',
            'info' => 'confound these ponies',
            'notInitialized' => null,
            'row' => array(
                0 => array(
                    'qt' => 3,
                    'item' => array(
                        MapAlias::CLASS_KEY => 'Product',
                        'title' => '5D mk III',
                        'price' => 2999
                    )
                ),
                1 => array(
                    'qt' => 1,
                    'item' => array(
                        MapAlias::CLASS_KEY => 'Product',
                        'title' => 'VTOL',
                        'price' => 6500,
                    )
                )
            )
        );
        return array(array($obj, $dump), array($obj2, $dump2));
    }

    public function getDataToDb()
    {
        $data = parent::getDataToDb();
        return array_merge($data, $this->getSampleTree());
    }

    public function getDataFromDb()
    {
        $data = parent::getDataFromDb();
        return array_merge($data, $this->getSampleTree());
    }

    public function testRestoreWithNonTrivialConstruct()
    {
        $obj = new Fixtures\VerifMethod(100);
        $dump = $this->mediator->recursivDesegregate($obj);
        $restore = $this->mediator->recursivCreate($dump);
        $this->assertInstanceOf('tests\Yuurei\Fixtures\VerifMethod', $restore);
        $this->assertEquals(119.6, $restore->getTotal());
    }

    public function testRootClassEmpty()
    {
        $obj = $this->mediator->recursivCreate(array(MapAlias::CLASS_KEY => null, 'answer' => 42));
        $this->assertInternalType('array', $obj);
    }

    public function testLeafClassEmpty()
    {
        $obj = $this->mediator->recursivCreate(
                array(
                    MapAlias::CLASS_KEY => 'default',
                    'child' => array(MapAlias::CLASS_KEY => null, 'answer' => 42)
                )
        );
        $this->assertInternalType('array', $obj->child);
    }

    public function testRootClassNotFound()
    {
        $obj = $this->mediator->recursivCreate(array(MapAlias::CLASS_KEY => 'Snark', 'answer' => 42));
        $this->assertInternalType('array', $obj);
    }

    public function testLeafClassNotFound()
    {
        $obj = $this->mediator->recursivCreate(
                array(
                    MapAlias::CLASS_KEY => 'default',
                    'child' => array(MapAlias::CLASS_KEY => 'Snark', 'answer' => 42)
                )
        );
        $this->assertInternalType('array', $obj->child);
    }

    /**
     * @expectedException Trismegiste\Alkahest\Transform\MappingException
     * @expectedExceptionMessage persistence
     */
    public function testClassNotAliased()
    {
        $this->mediator->recursivDesegregate(new \DOMDocument());
    }

    public function testSkippable()
    {
        $obj = new Fixtures\IntoVoid();
        $dump = $this->mediator->recursivDesegregate($obj);
        $this->assertNull($dump);
    }

    public function testChildSkippable()
    {
        $obj = new \stdClass();
        $obj->dummy = new Fixtures\IntoVoid();
        $obj->product = new Fixtures\Product("aaa", 23);
        $dump = $this->mediator->recursivDesegregate($obj);
        $this->assertNull($dump['dummy']);
        $this->assertNotNull($dump['product']);
        $this->assertArrayHasKey(MapAlias::CLASS_KEY, $dump['product']);
    }

    public function testCleanable()
    {
        $obj = new Fixtures\Bear();
        $dump = $this->mediator->recursivDesegregate($obj);
        $this->assertNull($dump['transient']);
        $this->assertArrayHasKey(MapAlias::CLASS_KEY, $dump);
        $this->assertEquals(42, $dump['answer']);
        $restore = $this->mediator->recursivCreate($dump);
        $this->assertEquals(range(1, 10), $restore->getTransient());
    }

}
