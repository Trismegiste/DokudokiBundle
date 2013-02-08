<?php

/*
 * mongosain
 */

namespace Trismegiste\DokudokiBundle\Tests\DependencyInjection;

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
            'server' => 'localhost:27017',
            'database' => 'Test',
            'collection' => 'sandbox',
            'fallback' => NULL,
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

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage SplFixedArray does not implement DynamicType
     */
    public function testFailOnNonDynamicType()
    {
        $cfg = $this->processConfig('config_fail2.yml');
    }

}

