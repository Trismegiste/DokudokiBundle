<?php

/*
 * DokudokiBundle
 */

namespace tests\DokudokiBundle;

use Trismegiste\DokudokiBundle\TrismegisteDokudokiBundle;

/**
 * TrismegisteDokudokiBundleTest tests the bundle class
 */
class TrismegisteDokudokiBundleTest extends \PHPUnit_Framework_TestCase
{

    protected $sut;

    protected function setUp()
    {
        $this->sut = new TrismegisteDokudokiBundle();
    }

    public function testExtension()
    {
        $ext = $this->sut->getContainerExtension();
        $this->assertInstanceOf('Trismegiste\DokudokiBundle\DependencyInjection\Extension', $ext);
        $ext2 = $this->sut->getContainerExtension();
        $this->assertSame($ext2, $ext);
    }

}
