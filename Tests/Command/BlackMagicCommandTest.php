<?php

/*
 * Dokudokibundle
 */

namespace tests\Command;

use Trismegiste\DokudokiBundle\Command\BlackMagicCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * BlackMagicCommandTest tests the command BlackMagicCommand
 *
 * @author flo
 */
class BlackMagicCommandTest extends \PHPUnit_Framework_TestCase
{

    protected $application;

    protected function setUp()
    {
        $this->application = new Application();
        $command = new BlackMagicCommand();
        $command->setContainer($this->getContainer());
        $this->application->add($command);
    }

    protected function getContainer()
    {
        $container = new \Symfony\Component\DependencyInjection\Container();
        // filling container with mockup of MongoCollection
        $collection = $this->getMockBuilder('MongoCollection')
                ->disableOriginalConstructor()
                ->setMethods(array('find'))
                ->getMock();
        $collection->expects($this->any())
                ->method('find')
                ->will($this->returnValue(array(
                            array('_id' => $this->getMock('MongoId'), '-class' => 'product', 'data' => 73),
                            array('_id' => $this->getMock('MongoId'), '-class' => 'user', 'answer' => 42)
                        )));
        $migration = new \Trismegiste\DokudokiBundle\Migration\BlackToWhiteMagic($collection, array());
        $container->set('dokudoki.migration.black2white', $migration);

        return $container;
    }

    public function testAnalyse()
    {
        $fchReport = tempnam(sys_get_temp_dir(), 'report');
        $command = $this->application->find('dokudoki:blackmagic');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'action' => 'analyse',
            '--config' => $fchReport
        ));

        $parsed = \Symfony\Component\Yaml\Yaml::parse(file_get_contents($fchReport));
        $this->assertEquals(array(
            'alias' => array(
                'product' => array(
                    'fqcn' => 'Not\Found\FQCN\Product',
                    'properties' => array('_id', 'data')
                ),
                'user' => array(
                    'fqcn' => 'Not\Found\FQCN\User',
                    'properties' => array('_id', 'answer')
            ))), $parsed);

        return $fchReport;
    }

    /**
     * @depends testAnalyse
     */
    public function testGeneration($report)
    {
        $command = $this->application->find('dokudoki:blackmagic');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
                array('command' => $command->getName(),
                    'action' => 'generate',
                    '--config' => $report)
        );
    }

    public function testUnknownCmd()
    {
        $command = $this->application->find('dokudoki:blackmagic');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
                array('command' => $command->getName(), 'action' => 'reaction')
        );
        $this->assertStringStartsWith('Unknown Command reaction', $commandTester->getDisplay());
    }

}