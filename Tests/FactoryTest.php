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

    public function testExperim()
    {
        $service = new Factory();

        $obj = new Cart("86 fdfg de fdf");
        $obj->addItem(3, new Product('EF85L', 1999));
        $obj->addItem(1, new Product('Bike', 650));

        $dump = $service->desegregation($obj);
        $restore = $service->create($dump);
        $this->assertEquals($obj, $restore);
    }

}

class Cart
{

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
