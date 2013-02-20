<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Command;

use Trismegiste\DokudokiBundle\Command\InvocationCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * InvocationCommandTest tests the command InvocationCommand
 *
 * @author flo
 */
class InvocationCommandTest extends \PHPUnit_Framework_TestCase
{

    protected $application;

    protected function setUp()
    {
        $this->application = new Application();
        $command = new InvocationCommand();
        $command->setContainer($this->getContainer());
        $this->application->add($command);
    }

    protected function getContainer()
    {
        $container = new \Symfony\Component\DependencyInjection\Container();
        $collection = $this->getMockBuilder('MongoCollection')
                ->disableOriginalConstructor()
                ->setMethods(array('find'))
                ->getMock();
        $collection->expects($this->once())
                ->method('find')
                ->will($this->returnValue(array(array('_id' => $this->getMock('MongoId'), '-fqcn' => 'H2G2', 'answer' => 42))));

        $container->set('dokudoki.collection', $collection);

        return $container;
    }

    public function testExecute()
    {
        $command = $this->application->find('dokudoki:invocation');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName(), 'action' => 'analyse'));

        echo $commandTester->getDisplay();
    }

}