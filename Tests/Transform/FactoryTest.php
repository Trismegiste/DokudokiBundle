<?php

/*
 * Dokudoki
 */

namespace Trismegiste\DokudokiBundle\Transform\Tests;

use Trismegiste\DokudokiBundle\Transform\Factory;

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
        $obj = new \stdClass();
        $obj->answer = 42;
        $dump = array('_class' => 'stdClass', 'answer'=>42);
        
        $obj2 = new Cart("86 fdfg de fdf");
        $obj2->info = 'nothing to say';
        $obj2->addItem(3, new Product('EF85L', 1999));
        $obj2->addItem(1, new Product('Bike', 650));

        $dump2 = array(
            '_class' => __NAMESPACE__ . '\Cart',
            'address' => '86 fdfg de fdf',
            'info' => 'nothing to say',
            'row' => array(
                0 => array(
                    'qt' => 3,
                    'item' => array(
                        '_class' => __NAMESPACE__ . '\Product',
                        'title' => 'EF85L',
                        'price' => 1999
                    )
                ),
                1 => array(
                    'qt' => 1,
                    'item' => array(
                        '_class' => __NAMESPACE__ . '\Product',
                        'title' => 'Bike',
                        'price' => 650,
                    )
                )
            )
        );
        return array(array($obj, $dump), array($obj2, $dump2) );
    }

    /**
     * @dataProvider getSampleTree
     */
    public function testDesegregate($obj, $dumpEqv)
    {
        $dump = $this->service->desegregate($obj);
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

    static public $transientProp = 123;

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
