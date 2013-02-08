<?php

/*
 * Dokudoki test
 */

namespace Trismegiste\DokudokiBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Trismegiste\DokudokiBundle\DependencyInjection\Extension;

/**
 * ExtensionTest is a test for the building of services provided by this bundle
 *
 * @author florent
 */
class ExtensionTest extends \PHPUnit_Framework_TestCase
{

    protected $container;

    protected function setUp()
    {
        $this->container = new ContainerBuilder();
        $extension = new Extension();
        $fullConfig = array(
            'server' => 'localhost:27017',
            'database' => 'Test',
            'collection' => 'sandbox',
            'alias' => array(
                'default' => 'stdClass',
                'cart' => 'Trismegiste\DokudokiBundle\Tests\Fixtures\Cart',
                'hybernate' => 'Trismegiste\DokudokiBundle\Tests\Fixtures\Bear',
                'product' => 'Trismegiste\DokudokiBundle\Tests\Fixtures\Product',
                'checkCall' => 'Trismegiste\DokudokiBundle\Tests\Fixtures\VerifMethod',
            )
        );
        $extension->load(array($fullConfig), $this->container);
    }

    protected function tearDown()
    {
        unset($this->container);
    }

    public function testConfigRoot()
    {
        $extension = new Extension();
        $this->assertEquals('dokudoki', $extension->getAlias());
    }

    public function testServiceConnector()
    {
        $this->assertInstanceOf('Trismegiste\DokudokiBundle\Persistence\Connector', $this->container->get('dokudoki.connector'));
    }

    public function testServiceCollection()
    {
        $this->assertInstanceOf('MongoCollection', $this->container->get('dokudoki.collection'));
    }

    public function testServiceBuilder()
    {
        $this->assertInstanceOf('Trismegiste\DokudokiBundle\Transform\Delegation\Stage\BlackMagic', $this->container->get('dokudoki.stage.blackmagic'));
        $this->assertInstanceOf('Trismegiste\DokudokiBundle\Transform\Delegation\Stage\WhiteMagic', $this->container->get('dokudoki.stage.whitemagic'));
        $this->assertInstanceOf('Trismegiste\DokudokiBundle\Transform\Delegation\Stage\Invocation', $this->container->get('dokudoki.stage.invocation'));
        $this->assertInstanceOf('Trismegiste\DokudokiBundle\Transform\Delegation\Stage\Hoodoo', $this->container->get('dokudoki.stage.hoodoo'));
    }

    //        $this->assertInstanceOf('Trismegiste\DokudokiBundle\Model\Factory', $this->container->get('mongosapin.factory'));
//        $this->assertInstanceOf('Trismegiste\DokudokiBundle\Persistence\Repository', $this->container->get('mongosapin.repository'));
//        $this->assertInstanceOf('Trismegiste\DokudokiBundle\Form\MagicFormType', $this->container->get('magic_form'));
}