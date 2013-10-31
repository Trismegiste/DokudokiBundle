<?php

/*
 * DokudokiBundle
 */

namespace tests\DokudokiBundle\Transform\Mediator\Colleague;

use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\MapMagic;
use Trismegiste\Yuurei\Transform\Mediator\Mediator;
use Trismegiste\DokudokiBundle\Magic\Document;
use tests\Yuurei\Transform\Mediator\Colleague\MapperTestTemplate;

/**
 * Test for MapMagic
 *
 * @author flo
 */
class MapMagicTest extends MapperTestTemplate
{

    protected function createMapper()
    {
        return new MapMagic($this->createMediatorMockup());
    }

    public function getDataFromDb()
    {
        $obj = new Document('proto');
        $obj->setAnswer(42);
        $dump = array(Document::classKey => 'proto', 'answer' => 42);
        return array(array($dump, $obj));
    }

    public function getDataToDb()
    {
        $obj = new Document('proto');
        $obj->setAnswer(42);
        $dump = array(Document::classKey => 'proto', 'answer' => 42);
        return array(array($obj, $dump));
    }

    public function getResponsibleDataToDb()
    {
        return array(array(new Document('dummy')));
    }

    public function getResponsibleDataFromDb()
    {
        return array(array(array(Document::classKey => 'hello')));
    }

    public function getNotResponsibleDataToDb()
    {
        return array(array(new \stdClass()), array(42), array(array('hello')));
    }

    public function getNotResponsibleDataFromDb()
    {
        return array(array(null), array(array('prop' => 'hello')), array(new \MongoDate()));
    }

}