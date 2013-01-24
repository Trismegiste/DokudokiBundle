<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Transform\Mediator\Colleague;

use Trismegiste\DokudokiBundle\Transform\Mediator\Colleague\MongoBinData;
use Trismegiste\DokudokiBundle\Transform\Mediator\Mediator;

/**
 * Test for MongoBinData
 *
 * @author flo
 */
class MongoBinDataTest extends MapperTestTemplate
{

    protected function createMapper()
    {
        $mediator = $this->getMockForAbstractClass('Trismegiste\DokudokiBundle\Transform\Mediator\AbstractMediator');
        return new MongoBinData($mediator);
    }

    public function getDataFromDb()
    {
        $obj = new \MongoBinData('bazinga', 2);
        return array(array($obj, clone $obj));
    }

    public function getDataToDb()
    {
        return $this->getDataFromDb();
    }

}