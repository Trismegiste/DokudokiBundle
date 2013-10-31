<?php

/*
 * Yuurei
 */

namespace tests\Yuurei\Transform\Delegation\Stage;

use Trismegiste\Yuurei\Transform\Delegation\MappingDirector;

/**
 * Template test for Mediator created by a builder
 *
 * @author flo
 */
abstract class AbstractStageTest extends \PHPUnit_Framework_TestCase
{

    protected $mediator;

    protected function setUp()
    {
        $director = new MappingDirector();
        $bluePrint = $this->createBuilder();
        $this->mediator = $director->create($bluePrint);
    }

    protected function tearDown()
    {
        unset($this->mediator);
    }

    abstract protected function createBuilder();

    public function testMediator()
    {
        $this->assertInstanceOf('Trismegiste\Yuurei\Transform\Mediator\Mediator', $this->mediator);
    }

    protected function getSymetricData()
    {
        $sample = array(null, 42, 3.141592, true, 'tribble', array('Ar' => 6));
        $sample[] = array('root' => array('trunk' => array('branch' => array('leaf'))));
        $sample[] = new \MongoBinData('some blob', 2);
        $sample[] = new \MongoId();
        $compare = array();
        foreach ($sample as $val) {
            $compare[] = array($val, $val);
        }

        return $compare;
    }

    /**
     * A list of couple ( memory representation , database representation )
     */
    public function getDataFromDb()
    {
        $compare = $this->getSymetricData();
        $compare[] = array(new \DateTime(), new \MongoDate());
        return $compare;
    }

    public function getDataToDb()
    {
        $compare = $this->getSymetricData();
        $compare[] = array(fopen(__FILE__, 'r'), null);
        $compare[] = array(new \DateTime(), new \MongoDate(time(), 0));
        return $compare;
    }

    /**
     * @dataProvider getDataToDb
     */
    public function testDesegregateCommon($obj, $db)
    {
        $dump = $this->mediator->recursivDesegregate($obj);
        $this->assertEquals($db, $dump);
    }

    /**
     * @dataProvider getDataFromDb
     */
    public function testCreateCommon($obj, $db)
    {
        $dump = $this->mediator->recursivCreate($db);
        $this->assertEquals($obj, $dump);
    }

}
