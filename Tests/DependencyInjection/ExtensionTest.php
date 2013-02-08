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

    public function testServiceConnector()
    {
        $this->assertInstanceOf('Trismegiste\DokudokiBundle\Persistence\Connector', $this->container->get('dokudoki.connector'));
    }

    public function testServiceCollection()
    {
        $this->assertInstanceOf('MongoCollection', $this->container->get('dokudoki.collection'));
    }

    //        $this->assertInstanceOf('Trismegiste\DokudokiBundle\Model\Factory', $this->container->get('mongosapin.factory'));
//        $this->assertInstanceOf('Trismegiste\DokudokiBundle\Persistence\Repository', $this->container->get('mongosapin.repository'));
//        $this->assertInstanceOf('Trismegiste\DokudokiBundle\Form\MagicFormType', $this->container->get('magic_form'));
}