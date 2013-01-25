<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Transform\Mediator\Colleague;

use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\MapMagic;
use Trismegiste\DokudokiBundle\Transform\Mediator\Mediator;
use Trismegiste\DokudokiBundle\Magic\Document;

/**
 * Test for MapMagic
 *
 * @author flo
 */
class MapMagicTest extends MapperTestTemplate
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

        return new MapMagic($mediator);
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