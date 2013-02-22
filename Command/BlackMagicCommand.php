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
 * BlackMagicCommand is ...
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
                ->setDescription('Analytics and generation for BlackMagic and Hoodoo stages')
                ->addArgument('action', InputArgument::REQUIRED, 'analyse|generate')
                ->addOption('config', null, InputOption::VALUE_REQUIRED, 'The statistics filename to dump/use', 'blackmagic.yml')
                ->addOption('missing-only', null, InputOption::VALUE_NONE, 'Finds only the missing aliases from the configuration');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cmd = $input->getArgument('action');
        $filename = $input->getOption('config');
        switch ($cmd) {
            case 'analyse' : $this->executeAnalyse($filename, $output);
                break;
            case 'generate' : $this->executeGenerate($filename, $output);
                break;
            default:
                $output->writeln("<error>Unknown Command $cmd</error>");
        }
    }

    protected function executeAnalyse($filename, OutputInterface $output)
    {
        $collection = $this->container->get('dokudoki.collection');
        $service = new BlackToWhiteMagic($collection, array());
        $report = $service->analyse();
        $output->writeln("dokudoki:");
        $output->writeln(str_repeat(' ', 4) . "alias:");
        $dumpConfig = array();
        foreach ($report['found'] as $alias => $counter) {
            $dumpConfig['alias'][$alias]['fqcn'] = 'F\Q\C\N';
            $output->writeln(str_repeat(' ', 8) . "$alias: F\\Q\\C\\N");
            if (array_key_exists($alias, $report['properties'])) {
                $dumpConfig['alias'][$alias]['property'] = array();
                foreach ($report['properties'][$alias] as $prop => $dummy) {
                    $dumpConfig['alias'][$alias]['property'][] = $prop;
                }
            }
        }
        file_put_contents($filename, \Symfony\Component\Yaml\Yaml::dump($dumpConfig, 4));
    }

    protected function executeGenerate($filename, OutputInterface $output)
    {
        $collection = $this->container->get('dokudoki.collection');
        $service = new BlackToWhiteMagic($collection, array());
        $cfg = \Symfony\Component\Yaml\Yaml::parse(file_get_contents($filename));
        $generatedClass = $service->generate($cfg['alias']);
        foreach ($generatedClass as $idx => $content) {
            file_put_contents(dirname($filename) . "/class$idx.php", $content);
        }
        $output->writeln(count($generatedClass) . " classes were generated.");
    }

}