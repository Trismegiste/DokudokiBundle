<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Transform\Mediator\Colleague;

use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\MapObject;
use Trismegiste\DokudokiBundle\Transform\Mediator\Mediator;

/**
 * Test for MapObject
 *
 * @author flo
 */
class MapObjectTest extends MapperTestTemplate
{

    protected function createMapper()
    {
        $mediator = $this->getMockForAbstractClass('Trismegiste\DokudokiBundle\Transform\Mediator\AbstractMediator');
        $mediator->expects($this->any())
                ->method('recursivCreate')
                ->will($this->returnArgument(0));
        $mediator->expects($this->any())
                ->method('recursivDesegregate')
                ->will($this->returnArgument(0));

        return new MapObject($mediator);
    }

    public function getDataFromDb()
    {
        $obj = new \stdClass();
        $obj->answer = 42;
        $dump = array(Mediator::FQCN_KEY => 'stdClass', 'answer' => 42);
        return array(array($dump, $obj));
    }

    public function getDataToDb()
    {
        $obj = new \stdClass();
        $obj->answer = 42;
        $dump = array(Mediator::FQCN_KEY => 'stdClass', 'answer' => 42);
        return array(array($obj, $dump));
    }

    public function getResponsibleDataToDb()
    {
        return array(array(new \stdClass()));
    }

    public function getResponsibleDataFromDb()
    {
        return array(array(array(Mediator::FQCN_KEY => 'hello')));
    }

    public function getNotResponsibleDataToDb()
    {
        return array(array(null), array(42), array(array('hello')));
    }

    public function getNotResponsibleDataFromDb()
    {
        return array(array(null), array(array('prop' => 'hello')), array(new \MongoDate()));
    }

}