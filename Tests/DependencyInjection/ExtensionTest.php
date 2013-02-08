<?php

/*
 * mysandbox
 */

namespace Trismegiste\DokudokiBundle\Tests\DependencyInjection;

use Trismegiste\DokudokiBundle\Tests\Functional;

/**
 * ExtensionTest is a test for the building of services provided by this bundle
 *
 * @author florent
 */
class ExtensionTest extends Functional
{

    protected $container;
    protected $config;

    protected function setUp()
    {
        parent::setUp();
        $this->container = $this->kernel->getContainer();
    }

    public function testServiceCreation()
    {
        $this->assertInstanceOf('Trismegiste\DokudokiBundle\Persistence\Connector', $this->container->get('mongosapin.connector'));
        $this->assertInstanceOf('MongoCollection', $this->container->get('mongosapin.collection'));
        $this->assertInstanceOf('Trismegiste\DokudokiBundle\Model\Factory', $this->container->get('mongosapin.factory'));
        $this->assertInstanceOf('Trismegiste\DokudokiBundle\Persistence\Repository', $this->container->get('mongosapin.repository'));
        $this->assertInstanceOf('Trismegiste\DokudokiBundle\Form\MagicFormType', $this->container->get('magic_form'));
    }

}