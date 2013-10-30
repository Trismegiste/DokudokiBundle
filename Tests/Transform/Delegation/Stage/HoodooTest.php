<?php

/*
 * DokudokiBundle
 */

namespace tests\Transform\Delegation\Stage;

use Trismegiste\DokudokiBundle\Transform\Delegation\Stage\Hoodoo;
use tests\Fixtures;
use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\MapAlias;
use Trismegiste\DokudokiBundle\Magic\Document;

/**
 * test for Mediator created by Hoodoo builder
 *
 */
class HoodooTest extends AbstractStageTest
{

    protected function createBuilder()
    {
        $fixture = 'tests\Fixtures';
        $alias = array(
            'default' => 'stdClass',
            'Cart' => $fixture . '\Cart',
            'Product' => $fixture . '\Product',
            'NonTrivial' => $fixture . '\VerifMethod',
            'Hibernate' => $fixture . '\Bear',
        );
        return new Hoodoo($alias);
    }

    protected function getSampleTreeMagicDocument()
    {
        $couple = array();
        $obj = new Document('root');
        $obj->setTrunk(new Document('trunk'));
        $obj->getTrunk()->setBranch(new Document('branch'));
        $obj->getTrunk()->getBranch()->setLeaf('maple');
        $db = array(
            Document::classKey => 'root',
            'trunk' => array(
                Document::classKey => 'trunk',
                'branch' => array(
                    Document::classKey => 'branch',
                    'leaf' => 'maple'
                )
            )
        );
        $provider = new Fixtures\MagicFixture();
        $couple[] = array($provider->getFullTreeObject(), $provider->getFullTreeFlat());
        $couple[] = array($obj, $db);
        return $couple;
    }

    public function getSampleTreeStandardClass()
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
        return array_merge($data, $this->getSampleTreeStandardClass(), $this->getSampleTreeMagicDocument());
    }

    public function getDataFromDb()
    {
        $data = parent::getDataFromDb();
        return array_merge($data, $this->getSampleTreeStandardClass(), $this->getSampleTreeMagicDocument());
    }

    public function testFusionOfBlackAndWhite()
    {
        $this->assertEquals(MapAlias::CLASS_KEY, Document::classKey);
    }

    public function testRestoreWithNonTrivialConstruct()
    {
        $obj = new Fixtures\VerifMethod(100);
        $dump = $this->mediator->recursivDesegregate($obj);
        $restore = $this->mediator->recursivCreate($dump);
        $this->assertInstanceOf('tests\Fixtures\VerifMethod', $restore);
        $this->assertEquals(119.6, $restore->getTotal());
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage No class type defined for DynamicType
     */
    public function testRootClassEmpty()
    {
        $obj = $this->mediator->recursivCreate(array(MapAlias::CLASS_KEY => null, 'answer' => 42));
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage No class type defined for DynamicType
     */
    public function testLeafClassEmpty()
    {
        $obj = $this->mediator->recursivCreate(
                array(
                    MapAlias::CLASS_KEY => 'default',
                    'child' => array(MapAlias::CLASS_KEY => null, 'answer' => 42)
                )
        );
    }

    public function testRootClassNotFound()
    {
        $obj = $this->mediator->recursivCreate(array(Document::classKey => 'Snark', 'answer' => 42));
        $this->assertInstanceOf('Trismegiste\DokudokiBundle\Magic\Document', $obj);
    }

    public function testLeafClassNotFound()
    {
        $obj = $this->mediator->recursivCreate(
                array(
                    MapAlias::CLASS_KEY => 'default',
                    'child' => array(Document::classKey => 'Snark', 'answer' => 42)
                )
        );
        $this->assertInstanceOf('Trismegiste\DokudokiBundle\Magic\Document', $obj->child);
    }

    /**
     * @expectedException Trismegiste\DokudokiBundle\Transform\MappingException
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
