<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Transform\Mediator\Colleague;

use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\MapArray;
use Trismegiste\DokudokiBundle\Transform\Mediator\Mediator;

/**
 * Test for MapArray
 *
 * @author flo
 */
class MapArrayTest extends MapperTestTemplate
{

    protected function createMapper()
    {
        return new MapArray($this->createMediatorMockup());
    }

    public function getDataFromDb()
    {
        $dump = array('answer' => 42, 'word' => 'bazinga');
        return array(array($dump, $dump));
    }

    public function getDataToDb()
    {
        return $this->getDataFromDb();
    }

    public function getResponsibleDataToDb()
    {
        return array(array(array('hello')));
    }

    public function getResponsibleDataFromDb()
    {
        // MapArray CAN map an dumped object from DB. It's just MapObject which overrides its responsibility
        // Like BG said, it's not a bug it's a feature : you can restore an object without the need of model
        return array(array(array('hello'), array(array(Mediator::FQCN_KEY => 'hello'))));
    }

    public function getNotResponsibleDataToDb()
    {
        return array(array(null), array(new \stdClass()), array(new \DateTime()));
    }

    public function getNotResponsibleDataFromDb()
    {
        return array(array(null), array(new \MongoDate()));
    }

}