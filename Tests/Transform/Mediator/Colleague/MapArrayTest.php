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
        $mediator = $this->getMockForAbstractClass('Trismegiste\DokudokiBundle\Transform\Mediator\AbstractMediator');
        $mediator->expects($this->any())
                ->method('recursivCreate')
                ->will($this->returnArgument(0));
        $mediator->expects($this->any())
                ->method('recursivDesegregate')
                ->will($this->returnArgument(0));

        return new MapArray($mediator);
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
        return $this->getResponsibleDataToDb();
    }

    public function getNotResponsibleDataToDb()
    {
        return array(array(null), array(new \stdClass()), array(new \DateTime()));
    }

    public function getNotResponsibleDataFromDb()
    {
        return array(array(null), array(array(Mediator::FQCN_KEY => 'hello')), array(new \MongoDate()));
    }

}