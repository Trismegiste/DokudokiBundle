<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Transform\Mediator\Colleague;

use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\MapNullable;

/**
 * Test for MapNullable
 *
 * @author flo
 */
class MapNullableTest extends MapperTestTemplate
{

    protected function createMapper()
    {
        $mediator = $this->getMockForAbstractClass('Trismegiste\DokudokiBundle\Transform\Mediator\AbstractMediator');
        return new MapNullable($mediator);
    }

    public function getDataFromDb()
    {
        return array(array(null, null));
    }

    public function getDataToDb()
    {
        $fch = fopen(__FILE__, 'r');
        return array(array(null, null), array($fch, null));
    }

}