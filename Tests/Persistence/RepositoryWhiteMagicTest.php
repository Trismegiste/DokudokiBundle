<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Persistence;

use Trismegiste\DokudokiBundle\Persistence\Repository;
use Trismegiste\DokudokiBundle\Facade\Provider;
use Trismegiste\DokudokiBundle\Tests\Fixtures;
use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\MapAlias;
/**
 * Test repository with WhiteMagic stage
 *
 * @author flo
 */
class RepositoryWhiteMagicTest extends RepositoryTestTemplate
{

    protected function createBuilder()
    {
        $fixture = 'Trismegiste\DokudokiBundle\Tests\Fixtures';
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
        $obj = new \Trismegiste\DokudokiBundle\Tests\Fixtures\Simple();
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

}
