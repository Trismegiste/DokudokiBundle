<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Transform\Mediator\Colleague;

use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\PhpCollection;
use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\MapObject;

/**
 * PhpCollectionTest tests PhpCollection
 */
class PhpCollectionTest extends MapperTestTemplate
{

    protected function createMapper()
    {
        return new PhpCollection($this->createMediatorMockup());
    }

    public function getDataFromDb()
    {
        $fixture = array('answer' => 42, 'word' => 'bazinga');
        $obj = new \ArrayObject($fixture);
        $dump[MapObject::FQCN_KEY] = 'ArrayObject';
        $dump['content'] = $fixture;

        return array(array($dump, $obj));
    }

    public function getDataToDb()
    {
        $fixture = array('answer' => 42, 'word' => 'bazinga');
        $obj = new \ArrayObject($fixture);
        $dump[MapObject::FQCN_KEY] = 'ArrayObject';
        $dump['content'] = $fixture;

        return array(array($obj, $dump));
    }

    public function getResponsibleDataToDb()
    {
        return array(
            array(new \ArrayObject()),
            array(new \SplObjectStorage())
        );
    }

    public function getResponsibleDataFromDb()
    {
        return array(
            array(array(MapObject::FQCN_KEY => 'ArrayObject')),
            array(array(MapObject::FQCN_KEY => 'SplObjectStorage'))
        );
    }

    public function getNotResponsibleDataToDb()
    {
        return array(array(null), array(42), array(new \stdClass()), array(array('hello' => 42)));
    }

    public function getNotResponsibleDataFromDb()
    {
        return array(array(null), array(MapObject::FQCN_KEY => 'stdClass', 'prop' => 'hello'));
    }

}