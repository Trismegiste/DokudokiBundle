<?php

/*
 * DokudoiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Utils;

use Trismegiste\DokudokiBundle\Utils\ReflectionClassBC;

/**
 * Description of ReflectionClassBCTest
 *
 * @author flo
 */
class ReflectionClassBCTest extends \PHPUnit_Framework_TestCase
{

    public function testHack()
    {
        $refl = new \Trismegiste\DokudokiBundle\Utils\InjectionClass('DateTime');
        $obj = $refl->newInstanceWithoutConstructor();
        foreach (array(
    'date' => '2013-01-20 21:55:10',
    'timezone_type' => 3,
    'timezone' => 'UTC'
        ) as $key => $val) {
            $refl->injectProperty($obj, $key, $val);
        }
        try {
            $obj->getTimestamp();
        } catch (Exception $e) {
            $this->markAsFail();
        }
        $refl->fixHackBC($obj);
        $this->assertEquals(1358718910, $obj->getTimestamp());
    }

}

