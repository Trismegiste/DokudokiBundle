<?php

/*
 * DokudokiBundle
 */

namespace tests\DokudokiBundle\Transform\Mediator\Colleague;

use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\MapAlias;
use tests\Alkahest\Transform\Mediator\Colleague\MapperTestTemplate;

/**
 * Test for MapAlias
 *
 * @author flo
 */
class MapAliasTest extends MapperTestTemplate
{

    protected function createMapper()
    {
        return new MapAlias($this->createMediatorMockup(), array('sample' => 'stdClass'));
    }

    public function getDataFromDb()
    {
        $obj = new \stdClass();
        $obj->answer = 42;
        $dump = array(MapAlias::CLASS_KEY => 'sample', 'answer' => 42);
        return array(array($dump, $obj));
    }

    public function getDataToDb()
    {
        $obj = new \stdClass();
        $obj->answer = 42;
        $dump = array(MapAlias::CLASS_KEY => 'sample', 'answer' => 42);
        return array(array($obj, $dump));
    }

    public function getResponsibleDataToDb()
    {
        return array(array(new \stdClass()));
    }

    public function getResponsibleDataFromDb()
    {
        return array(array(array(MapAlias::CLASS_KEY => 'sample')));
    }

    public function getNotResponsibleDataToDb()
    {
        return array(array(null), array(42), array(array('hello')), array(new \DateTime()));
    }

    public function getNotResponsibleDataFromDb()
    {
        return array(array(null), array(array(MapAlias::CLASS_KEY => 'hello')), array(new \MongoDate()));
    }

}