<?php

/*
 * DokudokiBundle
 */

namespace tests\DokudokiBundle\Persistence;

use Trismegiste\Yuurei\Persistence\Repository;
use Trismegiste\Yuurei\Facade\Provider;
use tests\Yuurei\Fixtures;
use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\MapAlias;
use tests\Yuurei\Persistence\RepositoryTestTemplate;

/**
 * Test repository with WhiteMagic stage
 *
 * @author flo
 */
class RepositoryWhiteMagicTest extends RepositoryTestTemplate
{

    protected function createBuilder()
    {
        $fixture = 'tests\Yuurei\Fixtures';
        $alias = array(
            'simple' => $fixture . '\Simple',
            'Order' => $fixture . '\Order',
            'Product' => $fixture . '\Product',
            'NonTrivial' => $fixture . '\VerifMethod',
            'Hibernate' => $fixture . '\Bear',
        );
        return new \Trismegiste\DokudokiBundle\Transform\Delegation\Stage\WhiteMagic($alias);
    }

    protected function getSimpleObject()
    {
        $obj = new Fixtures\Simple();
        $obj->answer = 42;
        return $obj;
    }

    protected function assertSimpleInsert(array $struc)
    {
        $this->assertEquals(42, $struc['answer']);
    }

    protected function editSimpleObject($obj)
    {
        $obj->answer = 73;
    }

    protected function assertEditedObject($obj)
    {
        $this->assertEquals(73, $obj->answer);
    }

    public function getComplexObject()
    {
        $obj = new Fixtures\Order("Bradburry appartments");
        $obj->info = 'confound these ponies';
        $obj->addItem(3, new Fixtures\Product('5D mk III', 2999));
        $obj->addItem(1, new Fixtures\Product('VTOL', 6500));

        $dump = array(
            MapAlias::CLASS_KEY => 'Order',
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

        return array(array($obj, $dump));
    }

    public function getCleanable()
    {
        $obj = new Fixtures\Simple();
        $obj->answer = new Fixtures\Bear();
        $dump = array(
            MapAlias::CLASS_KEY => 'simple',
            'answer' => array(
                MapAlias::CLASS_KEY => 'Hibernate',
                'answer' => 42,
                'transient' => null
            )
        );
        return array(array($obj, $dump));
    }

    /**
     * @dataProvider getCleanable
     */
    public function testCleanable($obj, $dump)
    {
        $this->assertNull($obj->getId());
        $this->repo->persist($obj);
        $this->assertInstanceOf('\MongoId', $obj->getId());
        $this->assertAttributeEquals(null, 'transient', $obj->answer); // beware of that
        // db
        $found = $this->collection->findOne(array('_id' => $obj->getId()));
        unset($found['_id']);
        $this->assertEquals($dump, $found);
        // restore
        $found = $this->repo->findByPk($obj->getId());
        $this->assertEquals(range(1, 10), $found->answer->getTransient());
    }

    public function testChildSkippable()
    {
        $obj = new Fixtures\Simple();
        $obj->dummy = new Fixtures\IntoVoid();
        $obj->product = new Fixtures\Product("aaa", 23);
        $this->repo->persist($obj);
        // restore
        $dump = $this->collection->findOne(array('_id' => $obj->getId()));
        $this->assertNull($dump['dummy']);
        $this->assertNotNull($dump['product']);
        $this->assertArrayHasKey(MapAlias::CLASS_KEY, $dump['product']);
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage A root entity cannot be Skippable
     */
    public function testSkippableRoot()
    {
        $obj = new Fixtures\NonSense();
        $this->repo->persist($obj);
    }

    /**
     * Person is Persistable but non aliased, that's why persisting it throws
     * a mapping exception
     * 
     * @expectedException Trismegiste\Yuurei\Transform\MappingException
     * @expectedExceptionMessage persistence
     */
    public function testNonAliasedPersistable()
    {
        $this->repo->persist(new Fixtures\Person());
    }

    protected function getQueryForComplexObject()
    {
        return ['row.0.item.title' => '5D mk III'];
    }

}
