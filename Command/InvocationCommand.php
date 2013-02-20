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

/**
 * InvocationCommand is ...
 *
 * @author flo
 */
class InvocationCommand extends Command implements ContainerAwareInterface
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
                ->setName('dokudoki:invocation')
                ->setDescription('Analytics and migration for Invocation stage')
                ->addArgument(
                        'action', InputArgument::REQUIRED, 'analyse|migrate'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cmd = $input->getArgument('action');
        switch ($cmd) {
            case 'analyse' : $this->executeAnalyse($output);
                break;
            case 'migrate' : $this->executeMigrate($output);
                break;
            default:
                $output->writeln("<error>Unknown Command $cmd</error>");
        }
    }

    protected function executeAnalyse(OutputInterface $output)
    {
        $collection = $this->container->get('dokudoki.collection');
        $service = new \Trismegiste\DokudokiBundle\Migration\InvocationToWhiteMagic($collection);
        $report = $service->analyse();
        $output->writeln("dokudoki:");
        $output->writeln(str_repeat(' ', 4) . "alias:");
        foreach ($report['fqcn'] as $key => $dummy) {
            preg_match('#([^\\\\]+)$#', $key, $extract);
            $output->writeln(str_repeat(' ', 8) . "{$extract[1]}: $key");
        }
        $output->writeln(str_repeat(' ', 4) . "missing:");
        foreach ($report['missing'] as $key => $dummy) {
            $output->writeln(str_repeat(' ', 8) . "notFound: $key");
        }
    }

}