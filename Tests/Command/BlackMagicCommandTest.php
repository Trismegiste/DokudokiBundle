<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Command;

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
        $container->set('dokudoki.collection', $collection);

        return $container;
    }

    public function testAnalyse()
    {
        $command = $this->application->find('dokudoki:blackmagic');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName(), 'action' => 'analyse'));

        $parsed = \Symfony\Component\Yaml\Yaml::parse($commandTester->getDisplay());
        $this->assertEquals(
                array('dokudoki' => array(
                'alias' => array('product' => 'F\Q\C\N', 'user' => 'F\Q\C\N')
                ))
                , $parsed);
    }

    public function testGeneration()
    {
        $command = $this->application->find('dokudoki:blackmagic');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName(), 'action' => 'generate'));
    }

}