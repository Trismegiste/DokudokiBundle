<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Transform\Mediator\Colleague;

use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\MongoInvariant;
use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\MapObject;

/**
 * Test for MongoInvariant
 *
 * @author flo
 */
class MongoInvariantTest extends MapperTestTemplate
{

    protected function createMapper()
    {
        $mediator = $this->getMockForAbstractClass('Trismegiste\DokudokiBundle\Transform\Mediator\AbstractMediator');
        return new MongoInvariant($mediator);
    }

    public function getDataFromDb()
    {
        $obj = new \MongoBinData('bazinga', 2);
        $id = new \MongoId();
        return array(array($obj, clone $obj), array($id, $id));
    }

    public function getDataToDb()
    {
        return $this->getDataFromDb();
    }

    public function getResponsibleDataToDb()
    {
        return array(array(new \MongoId(), new \MongoBinData('mahalo', 2)));
    }

    public function getResponsibleDataFromDb()
    {
        return $this->getResponsibleDataToDb();
    }

    public function getNotResponsibleDataToDb()
    {
        return array(array(new \stdClass()), array(new \MongoDate()));
    }

    public function getNotResponsibleDataFromDb()
    {
        return array(array(new \MongoDate()), array(array(MapObject::FQCN_KEY => 'MongoBinData')));
    }

}