<?php

/*
 * DokudoiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Utils;

use Trismegiste\DokudokiBundle\Utils\InjectionClass;

/**
 * Description of ReflectionClassBCTest
 *
 * @author flo
 */
class ReflectionClassBCTest extends \PHPUnit_Framework_TestCase
{

    public function getOneDate()
    {
        return array(array(array(
                    'date' => '2013-01-20 21:55:10',
                    'timezone_type' => 3,
                    'timezone' => 'UTC'
            )));
    }

    /**
     * @dataProvider getOneDate
     * @expectedException PHPUnit_Framework_Error
     */
    public function testErrorWhenNoWakeup($param)
    {
        $refl = new InjectionClass('DateTime');
        $obj = $refl->newInstanceWithoutConstructor();
        foreach ($param as $key => $val) {
            $refl->injectProperty($obj, $key, $val);
        }
        $obj->getTimestamp();
    }

    /**
     * @dataProvider getOneDate
     */
    public function testHackOk($param)
    {
        $refl = new InjectionClass('DateTime');
        $obj = $refl->newInstanceWithoutConstructor();
        foreach ($param as $key => $val) {
            $refl->injectProperty($obj, $key, $val);
        }
        $refl->fixHackBC($obj);
        $obj->getTimestamp();
        $this->assertEquals(1358718910, $obj->getTimestamp());
    }

}

