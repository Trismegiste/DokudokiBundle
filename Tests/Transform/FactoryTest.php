<?php

/*
 * Dokudoki
 */

namespace Trismegiste\DokudokiBundle\Transform\Tests;

use Trismegiste\DokudokiBundle\Transform\Factory;
use Trismegiste\DokudokiBundle\Transform\Mediator\Mediator;
use Trismegiste\DokudokiBundle\Transform\Skippable;

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
        $dump = array(Mediator::FQCN_KEY => 'stdClass', 'answer' => 42);

        $obj2 = new Cart("86 fdfg de fdf");
        $obj2->info = 'nothing to say';
        $obj2->addItem(3, new Product('EF85L', 1999));
        $obj2->addItem(1, new Product('Bike', 650));

        $dump2 = array(
            Mediator::FQCN_KEY => __NAMESPACE__ . '\Cart',
            'address' => '86 fdfg de fdf',
            'info' => 'nothing to say',
            'notInitialized' => null,
            'row' => array(
                0 => array(
                    'qt' => 3,
                    'item' => array(
                        Mediator::FQCN_KEY => __NAMESPACE__ . '\Product',
                        'title' => 'EF85L',
                        'price' => 1999
                    )
                ),
                1 => array(
                    'qt' => 1,
                    'item' => array(
                        Mediator::FQCN_KEY => __NAMESPACE__ . '\Product',
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

    public function testDate()
    {
        $obj = $this->service->create(array(Mediator::FQCN_KEY => 'stdClass', 'ts' => new \MongoDate()));
        $this->assertInstanceOf('DateTime', $obj->ts);
        $dump = $this->service->desegregate($obj);
        $this->assertInstanceOf('MongoDate', $dump['ts']);
        $this->assertEquals($obj->ts->getTimestamp(), $dump['ts']->sec);
    }

    public function testBinData()
    {
        $content = "something new";
        $obj = $this->service->create(array(Mediator::FQCN_KEY => 'stdClass', 'file' => new \MongoBinData($content, 2)));
        $this->assertInstanceOf('MongoBinData', $obj->file);
        $dump = $this->service->desegregate($obj);
        $this->assertInstanceOf('MongoBinData', $dump['file']);
        $this->assertEquals($content, $dump['file']->bin);
    }

    /**
     * @expectedException \DomainException
     */
    public function testClassEmpty()
    {
        $obj = $this->service->create(array(Mediator::FQCN_KEY => null, 'answer' => 42));
    }

    /**
     * @expectedException \DomainException
     */
    public function testClassNotFound()
    {
        $this->service->create(array(Mediator::FQCN_KEY => 'Snark', 'answer' => 42));
    }

    public function testSkippable()
    {
        $obj = new \stdClass();
        $obj->dummy = new IntoVoid();
        $obj->product = new Product("aaa", 23);
        $dump = $this->service->desegregate($obj);
        $this->assertNull($dump['dummy']);
        $this->assertNotNull($dump['product']);
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

class IntoVoid implements Skippable
{
    
}