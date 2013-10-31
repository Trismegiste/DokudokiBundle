<?php

/*
 * mongosain
 */

namespace tests\DependencyInjection;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\Definition\Processor;
use Trismegiste\DokudokiBundle\DependencyInjection\Configuration;

/**
 * ConfigurationTest is a unit test for configuration of this  bundle
 *
 * @author flo
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{

    protected function processConfig($fch)
    {
        $def = Yaml::parse(__DIR__ . '/' . $fch);
        $cfg = new Configuration();
        $proc = new Processor();

        return $proc->processConfiguration($cfg, array($def));
    }

    public function testForRequisites()
    {
        $cfg = $this->processConfig('config_minimal.yml');
        $expected = array(
            'stage' => 'dummy',
            'server' => 'localhost:27017',
            'database' => 'Test',
            'collection' => 'sandbox',
            'alias' => array()
        );
        $this->assertEquals($expected, $cfg);
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage FQCN NotFoundDummy does not exist
     */
    public function testFailOnNonExistingClass()
    {
        $cfg = $this->processConfig('config_fail1.yml');
    }

    public function testFullConfig()
    {
        $cfg = $this->processConfig('config_full.yml');
        $expected = array(
            'stage' => 'dummy',
            'server' => 'localhost:27017',
            'database' => 'Test',
            'collection' => 'sandbox',
            'alias' => array(
                'default' => 'stdClass',
                'cart' => 'tests\Yuurei\Fixtures\Cart',
                'hybernate' => 'tests\Yuurei\Fixtures\Bear',
                'product' => 'tests\Yuurei\Fixtures\Product',
                'checkCall' => 'tests\Yuurei\Fixtures\VerifMethod',
            )
        );
        $this->assertEquals($expected, $cfg);
    }

}

