<?php

/*
 * Dokudoki
 */

namespace Trismegiste\DokudokiBundle\Tests;

use Trismegiste\DokudokiBundle\Factory;

/**
 * FactoryTest test for Factory
 *
 * @author florent
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{

    protected $service;

    protected function setUp()
    {
        $this->service = new Factory();
    }

    protected function tearDown()
    {
        unset($this->service);
    }

    public function getSampleTree()
    {
        $obj = new Cart("86 fdfg de fdf");
        $obj->info = 'nothing to say';
        $obj->addItem(3, new Product('EF85L', 1999));
        $obj->addItem(1, new Product('Bike', 650));

        $dump = array(
            '_class' => 'Trismegiste\DokudokiBundle\Tests\Cart',
            'address' => '86 fdfg de fdf',
            'info' => 'nothing to say',
            'row' => array(
                0 => array(
                    'qt' => 3,
                    'item' => array(
                        '_class' => 'Trismegiste\DokudokiBundle\Tests\Product',
                        'title' => 'EF85L',
                        'price' => 1999
                    )
                ),
                1 => array(
                    'qt' => 1,
                    'item' => array(
                        '_class' => 'Trismegiste\DokudokiBundle\Tests\Product',
                        'title' => 'Bike',
                        'price' => 650,
                    )
                )
            )
        );
        return array(array($obj, $dump));
    }

    /**
     * @dataProvider getSampleTree
     */
    public function testDesegregate($obj, $dumpEqv)
    {
        $dump = $this->service->desegregation($obj);
        $this->assertEquals($dumpEqv, $dump);
    }

    /**
     * @dataProvider getSampleTree
     */
    public function testRestoring($obj, $dumpEqv)
    {
        $restore = $this->service->create($dumpEqv);
        $this->assertEquals($obj, $restore);
    }

}

class Cart
{

    public $info = '';
    protected $address;
    private $row = array();

    public function __construct($addr)
    {
        $this->address = $addr;
    }

    public function addItem($qt, Product $pro)
    {
        $this->row[] = array('qt' => $qt, 'item' => $pro);
    }

}

class Product
{

    private $title;
    protected $price = 0;

    public function __construct($tit, $pri)
    {
        $this->title = $tit;
        $this->price = $pri;
    }

}
