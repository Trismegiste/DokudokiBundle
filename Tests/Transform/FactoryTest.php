<?php

/*
 * Dokudoki
 */

namespace Trismegiste\DokudokiBundle\Transform\Tests;

use Trismegiste\DokudokiBundle\Transform\Transformer;
use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\MapObject;

require_once __DIR__ . '/ModelSample.php';

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
        $this->service = new Transformer();
    }

    protected function tearDown()
    {
        unset($this->service);
    }

    public function getSampleTree()
    {
        $obj = new \stdClass();
        $obj->answer = 42;
        $dump = array(MapObject::FQCN_KEY => 'stdClass', 'answer' => 42);

        $obj2 = new Cart("86 fdfg de fdf");
        $obj2->info = 'nothing to say';
        $obj2->addItem(3, new Product('EF85L', 1999));
        $obj2->addItem(1, new Product('Bike', 650));

        $dump2 = array(
            MapObject::FQCN_KEY => __NAMESPACE__ . '\Cart',
            'address' => '86 fdfg de fdf',
            'info' => 'nothing to say',
            'notInitialized' => null,
            'row' => array(
                0 => array(
                    'qt' => 3,
                    'item' => array(
                        MapObject::FQCN_KEY => __NAMESPACE__ . '\Product',
                        'title' => 'EF85L',
                        'price' => 1999
                    )
                ),
                1 => array(
                    'qt' => 1,
                    'item' => array(
                        MapObject::FQCN_KEY => __NAMESPACE__ . '\Product',
                        'title' => 'Bike',
                        'price' => 650,
                    )
                )
            )
        );
        return array(array($obj, $dump), array($obj2, $dump2));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testOnlyObject()
    {
        $dump = $this->service->desegregate(array('nawak'));
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
        $obj = $this->service->create(array(MapObject::FQCN_KEY => 'stdClass', 'ts' => new \MongoDate()));
        $this->assertInstanceOf('DateTime', $obj->ts);
        $dump = $this->service->desegregate($obj);
        $this->assertInstanceOf('MongoDate', $dump['ts']);
        $this->assertEquals($obj->ts->getTimestamp(), $dump['ts']->sec);
    }

    public function testBinData()
    {
        $content = "something new";
        $obj = $this->service->create(array(MapObject::FQCN_KEY => 'stdClass', 'file' => new \MongoBinData($content, 2)));
        $this->assertInstanceOf('MongoBinData', $obj->file);
        $dump = $this->service->desegregate($obj);
        $this->assertInstanceOf('MongoBinData', $dump['file']);
        $this->assertEquals($content, $dump['file']->bin);
    }

    /**
     * @expectedException \DomainException
     */
    public function testRootClassEmpty()
    {
        $obj = $this->service->create(array(MapObject::FQCN_KEY => null, 'answer' => 42));
    }

    /**
     * @expectedException \DomainException
     */
    public function testLeafClassEmpty()
    {
        $obj = $this->service->create(
                array(
                    MapObject::FQCN_KEY => 'stdClass',
                    'child' => array(MapObject::FQCN_KEY => null, 'answer' => 42)
                )
        );
    }

    /**
     * @expectedException \DomainException
     */
    public function testRootClassNotFound()
    {
        $this->service->create(array(MapObject::FQCN_KEY => 'Snark', 'answer' => 42));
    }

    /**
     * @expectedException \DomainException
     */
    public function testLeafClassNotFound()
    {
        $obj = $this->service->create(
                array(
                    MapObject::FQCN_KEY => 'stdClass',
                    'child' => array(MapObject::FQCN_KEY => 'Snark', 'answer' => 42)
                )
        );
    }

    /**
     * @expectedException \LogicException
     */
    public function testSkippable()
    {
        $obj = new IntoVoid();
        $dump = $this->service->desegregate($obj);
    }

    public function testChildSkippable()
    {
        $obj = new \stdClass();
        $obj->dummy = new IntoVoid();
        $obj->product = new Product("aaa", 23);
        $dump = $this->service->desegregate($obj);
        $this->assertNull($dump['dummy']);
        $this->assertNotNull($dump['product']);
    }

    public function testCleanable()
    {
        $obj = new Bear();
        $dump = $this->service->desegregate($obj);
        $this->assertNull($dump['transient']);
        $this->assertEquals(42, $dump['answer']);
        $restore = $this->service->create($dump);
        $this->assertEquals(range(1, 100), $restore->getTransient());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testEDxceptionForBadCreation()
    {
        $this->service->create(array('bazinga' => 73));
    }

}
