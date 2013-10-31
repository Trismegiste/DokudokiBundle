<?php

/*
 * Yuurei
 */

namespace tests\Yuurei\Transform\Mediator\Colleague;

use Trismegiste\Yuurei\Transform\Mediator\Colleague\MapScalar;

/**
 * Test for MapScalar
 *
 * @author flo
 */
class MapScalarTest extends MapperTestTemplate
{

    protected function createMapper()
    {
        $mediator = $this->getMockForAbstractClass('Trismegiste\Yuurei\Transform\Mediator\AbstractMediator');
        return new MapScalar($mediator);
    }

    public function getDataFromDb()
    {
        return array(array(42, 42), array(6.62, 6.62), array('Bazinga', 'Bazinga'), array(true, true));
    }

    public function getDataToDb()
    {
        return $this->getDataFromDb();
    }

    public function getResponsibleDataToDb()
    {
        return array(array(42), array(false), array('bazinga'), array(6.62));
    }

    public function getResponsibleDataFromDb()
    {
        return $this->getResponsibleDataToDb();
    }

    public function getNotResponsibleDataToDb()
    {
        return array(array(null), array(array('hello')), array(new \DateTime()));
    }

    public function getNotResponsibleDataFromDb()
    {
        return array(array(null), array(array('hello')), array(new \MongoDate()));
    }

}