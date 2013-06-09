<?php

/*
 * DokudoiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Utils;

use Trismegiste\DokudokiBundle\Utils\InjectionClass;

/**
 * InjectionClassTest tests a normal behavior with internal type
 */
class InjectionClassTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException ReflectionException
     */
    public function testErrorWhenNoWakeup()
    {
        $refl = new InjectionClass('DateTime');
        $obj = $refl->newInstanceWithoutConstructor();
    }

}

