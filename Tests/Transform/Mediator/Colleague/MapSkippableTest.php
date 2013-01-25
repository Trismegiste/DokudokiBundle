<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Transform\Mediator\Colleague;

use Trismegiste\DokudokiBundle\Transform\Tests\IntoVoid;
use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\MapSkippable;

require_once __DIR__ . '/../../ModelSample.php';

/**
 * MapSkippableTest is a ...
 *
 * @author florent
 */
class MapSkippableTest extends MapperTestTemplate
{

    protected function createMapper()
    {
        $mediator = $this->getMockForAbstractClass('Trismegiste\DokudokiBundle\Transform\Mediator\AbstractMediator');
        return new MapSkippable($mediator);
    }

    public function getDataFromDb()
    {

    }

    public function testMapFromDb()
    {

    }

    public function getDataToDb()
    {
        return array(array(new IntoVoid(), null));
    }

    public function getNotResponsibleDataFromDb()
    {
        return array(array(42));
    }

    public function getNotResponsibleDataToDb()
    {
        return array(array(new \stdClass()), array(42));
    }

    public function getResponsibleDataFromDb()
    {

    }

    public function testResponsibleFromDb()
    {

    }

    public function getResponsibleDataToDb()
    {
        return array(array(new IntoVoid()));
    }

}