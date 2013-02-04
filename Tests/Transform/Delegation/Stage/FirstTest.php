<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Transform\Delegation\Stage;

use Trismegiste\DokudokiBundle\Transform\Delegation\Stage\First;
use Trismegiste\DokudokiBundle\Tests\Fixtures;
use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\MapObject;

/**
 * test for Mediator created by First builder
 *
 * @author flo
 */
class FirstTest extends AbstractStageTest
{

    public function getSampleTree()
    {
        $obj = new \stdClass();
        $obj->answer = 42;
        $dump = array(MapObject::FQCN_KEY => 'stdClass', 'answer' => 42);

        $obj2 = new Fixtures\Cart("86 fdfg de fdf");
        $obj2->info = 'nothing to say';
        $obj2->addItem(3, new Fixtures\Product('EF85L', 1999));
        $obj2->addItem(1, new Fixtures\Product('Bike', 650));

        $fixture = 'Trismegiste\DokudokiBundle\Tests\Fixtures';
        $dump2 = array(
            MapObject::FQCN_KEY => $fixture . '\Cart',
            'address' => '86 fdfg de fdf',
            'info' => 'nothing to say',
            'notInitialized' => null,
            'row' => array(
                0 => array(
                    'qt' => 3,
                    'item' => array(
                        MapObject::FQCN_KEY => $fixture . '\Product',
                        'title' => 'EF85L',
                        'price' => 1999
                    )
                ),
                1 => array(
                    'qt' => 1,
                    'item' => array(
                        MapObject::FQCN_KEY => $fixture . '\Product',
                        'title' => 'Bike',
                        'price' => 650,
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

    protected function createBuilder()
    {
        return new First();
    }

}
