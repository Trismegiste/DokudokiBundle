<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Transform\Mediator\Colleague;

use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\DateObject;
use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\MapObject;

/**
 * Test for DateObject
 *
 * @author flo
 */
class DateObjectTest extends MapperTestTemplate
{

    protected function createMapper()
    {
        $mediator = $this->getMockForAbstractClass('Trismegiste\DokudokiBundle\Transform\Mediator\AbstractMediator');
        return new DateObject($mediator);
    }

    public function getDataFromDb()
    {
        return array(array(new \MongoDate(), new \DateTime()));
    }

    public function getDataToDb()
    {
        return array(array(new \DateTime(), new \MongoDate(time(), 0)));
    }

    public function getResponsibleDataToDb()
    {
        return array(array(new \DateTime()));
    }

    public function getResponsibleDataFromDb()
    {
        return array(array(new \MongoDate()));
    }

    public function getNotResponsibleDataToDb()
    {
        return array(array(new \stdClass()), array(new \MongoDate()));
    }

    public function getNotResponsibleDataFromDb()
    {
        return array(array(new \DateTime()), array(array(MapObject::FQCN_KEY => 'DateTime')));
    }

    /**
     * @expectedException LogicException
     */
    public function testMapMongoDateToDb()
    {
        $dump = $this->mapper->mapToDb(new \MongoDate());
    }

}