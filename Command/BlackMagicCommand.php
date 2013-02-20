<?php

/*
 * Dokudokibundle
 */

namespace Trismegiste\DokudokiBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Trismegiste\DokudokiBundle\Migration\BlackToWhiteMagic;

/**
 * InvocationCommand is ...
 *
 * @author flo
 */
class BlackMagicCommand extends Command implements ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @see ContainerAwareInterface::setContainer()
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    protected function configure()
    {
        $this
                ->setName('dokudoki:blackmagic')
                ->setDescription('Analytics and generation for BlackMagic stage')
                ->addArgument(
                        'action', InputArgument::REQUIRED, 'analyse|generate'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cmd = $input->getArgument('action');
        switch ($cmd) {
            case 'analyse' : $this->executeAnalyse($output);
                break;
            case 'generate' : $this->executeGenerate($output);
                break;
            default:
                $output->writeln("<error>Unknown Command $cmd</error>");
        }
    }

    protected function executeAnalyse(OutputInterface $output)
    {
        $collection = $this->container->get('dokudoki.collection');
        $service = new BlackToWhiteMagic($collection);
        $report = $service->analyse();
        $output->writeln("dokudoki:");
        $output->writeln(str_repeat(' ', 4) . "alias:");
        foreach ($report['found'] as $alias => $counter) {
            $output->writeln(str_repeat(' ', 8) . "$alias: F\\Q\\C\\N");
        }
    }

    protected function executeGenerate(OutputInterface $output)
    {
        $source = $this->container->get('dokudoki.repository.invocation');
        $destination = $this->container->get('dokudoki.repository.whitemagic');
        $collection = $this->container->get('dokudoki.collection');
        $service = new InvocationToWhiteMagic($collection);
        $migrated = $service->migrate($source, $destination);
        $output->writeln("$migrated root entities were migrated.");
    }

}