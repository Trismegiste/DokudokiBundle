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
        // filling container with mockup of MongoCollection
        $collection = $this->getMockBuilder('MongoCollection')
                ->disableOriginalConstructor()
                ->setMethods(array('find'))
                ->getMock();
        $collection->expects($this->once())
                ->method('find')
                ->will($this->returnValue(array(
                            array('_id' => $this->getMock('MongoId'), '-fqcn' => 'stdClass', 'data' => 73),
                            array('_id' => $this->getMock('MongoId'), '-fqcn' => 'H2G2', 'answer' => 42)
                        )));
        $container->set('dokudoki.collection', $collection);

        return $container;
    }

    public function testAnalyse()
    {
        $command = $this->application->find('dokudoki:invocation');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName(), 'action' => 'analyse'));

        $parsed = \Symfony\Component\Yaml\Yaml::parse($commandTester->getDisplay());
        $this->assertEquals(
                array('dokudoki' => array(
                'alias' => array('stdClass' => 'stdClass'),
                'missing' => array('notFound' => 'H2G2')
                ))
                , $parsed);
    }

    public function testMigrate()
    {
        $command = $this->application->find('dokudoki:invocation');
        // I am injecting more service in the container owned by the command (a little ugly, I confess)
        $refl = new \ReflectionObject($command);
        $prop = $refl->getProperty('container');
        $prop->setAccessible(true);
        $container = $prop->getValue($command);
        // filling container with mockup of Repository for Invocation stage
        $invoc = $this->getMockForAbstractClass('Trismegiste\DokudokiBundle\Persistence\RepositoryInterface');
        $invoc->expects($this->exactly(2))
                ->method('createFromDb')
                ->with($this->anything())
                ->will($this->returnValue($this->getMockForAbstractClass('Trismegiste\DokudokiBundle\Persistence\Persistable')));
        $container->set('dokudoki.repository.invocation', $invoc);
        // filling container with mockup of Repository for WhiteMagic stage
        $white = $this->getMockForAbstractClass('Trismegiste\DokudokiBundle\Persistence\RepositoryInterface');
        $white->expects($this->exactly(2))
                ->method('persist')
                ->with($this->anything());
        $container->set('dokudoki.repository.whitemagic', $white);

        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName(), 'action' => 'migrate'));
        $this->assertContains('2 root entities were migrated.', $commandTester->getDisplay());
    }

}