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
        $dump = array('_class' => 'stdClass', 'answer' => 42);

        $obj2 = new Cart("86 fdfg de fdf");
        $obj2->info = 'nothing to say';
        $obj2->addItem(3, new Product('EF85L', 1999));
        $obj2->addItem(1, new Product('Bike', 650));

        $dump2 = array(
            '_class' => __NAMESPACE__ . '\Cart',
            'address' => '86 fdfg de fdf',
            'info' => 'nothing to say',
            'notInitialized' => null,
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
        return array(array($obj, $dump), array($obj2, $dump2));
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

    public function testCallingRestore()
    {
        $obj = new VerifMethod(100);
        $dump = $this->service->desegregate($obj);
        $restore = $this->service->create($dump);
        $this->assertInstanceOf(__NAMESPACE__ . '\VerifMethod', $restore);
        $this->assertEquals(119.6, $restore->getTotal());
    }

    public function testInternalTypeRestore()
    {
        $now = new \DateTime();
        $obj = new \stdClass();
        $obj->example = clone $now;
        $dump = $this->service->desegregate($obj);
        $restore = $this->service->create($dump);
        $this->assertInstanceOf('DateTime', $restore->example);
        $this->assertEquals($now->getTimestamp(), $restore->example->getTimestamp());
    }

    public function notestMongoType()
    {
        $obj = $this->service->create(array('_class' => 'stdClass', 'ts' => new \MongoDate()));
        $this->assertInstanceOf('MongoDate', $obj->ts);
        $dump = $this->service->desegregate($obj);
        $this->assertInstanceOf('MongoDate', $dump['ts']);
    }

}

class Cart
{

    public $info = '';
    protected $address;
    private $row = array();
    protected $notInitialized;
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

class VerifMethod
{

    protected $vat = 19.6;
    protected $price;

    public function __construct($x)
    {
        $this->price = $x;
    }

    public function getTotal()
    {
        return $this->price * (1 + $this->vat / 100);
    }

}