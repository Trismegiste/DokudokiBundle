<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Transform\Mediator\Colleague;

use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\DateObject;
use Trismegiste\DokudokiBundle\Transform\Mediator\Mediator;

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
        return array(array(new \DateTime(), new \MongoDate()));
    }

}