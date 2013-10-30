<?php

/*
 * DokudokiBundle
 */

namespace tests\Transform\Mediator\Colleague;

use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\MapNullable;

/**
 * Test for MapNullable
 *
 * @author flo
 */
class MapNullableTest extends MapperTestTemplate
{

    protected function createMapper()
    {
        $mediator = $this->getMockForAbstractClass('Trismegiste\DokudokiBundle\Transform\Mediator\AbstractMediator');
        return new MapNullable($mediator);
    }

    public function getDataFromDb()
    {
        return array(array(null, null));
    }

    public function getDataToDb()
    {
        $fch = fopen(__FILE__, 'r');
        return array(array(null, null), array($fch, null));
    }

    public function getResponsibleDataToDb()
    {
        $fch = fopen(__FILE__, 'r');
        return array(array(null), array($fch));
    }

    public function getResponsibleDataFromDb()
    {
        return array(array(null));
    }

    public function getNotResponsibleDataToDb()
    {
        return array(array(42), array('bazinga'), array(new \stdClass()), array(new \DateTime()));
    }

    public function getNotResponsibleDataFromDb()
    {
        return array(array(42), array(new \MongoDate()));
    }

}