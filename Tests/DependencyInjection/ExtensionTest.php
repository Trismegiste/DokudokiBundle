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
        $server = (false !== getenv('SYMFONY__MONGODB__SERVER')) ? getenv('SYMFONY__MONGODB__SERVER') : 'localhost:27017';

        $this->container = new ContainerBuilder();
        $extension = new Extension();
        $fullConfig = array(
            'server' => $server,
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
        $this->container->compile();
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

    public function testFacade()
    {
        $this->assertInstanceOf('Trismegiste\DokudokiBundle\Facade\Provider', $this->container->get('dokudoki.facade'));
    }

    public function getStage()
    {
        $dump = array();
        foreach (array('blackmagic', 'invocation', 'whitemagic', 'hoodoo') as $key) {
            $param[] = array($key);
        }
        return $param;
    }

    /**
     * @dataProvider getStage
     */
    public function testRepository($stage)
    {
        $this->assertInstanceOf(
                'Trismegiste\DokudokiBundle\Persistence\Repository', $this->container->get('dokudoki.repository.' . $stage)
        );
    }

}