<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Transform\Mediator\Colleague;

use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\MapScalar;

/**
 * Test for MapScalar
 *
 * @author flo
 */
class MapScalarTest extends MapperTestTemplate
{

    protected function createMapper()
    {
        $mediator = $this->getMockForAbstractClass('Trismegiste\DokudokiBundle\Transform\Mediator\AbstractMediator');
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

}