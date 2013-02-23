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
        $service = $this->container->get('dokudoki.migration.black2white');
        $report = $service->filter();
        $output->writeln("Report:");
        $output->writeln("  Aliases: ".count($report['alias']));
        file_put_contents($filename, \Symfony\Component\Yaml\Yaml::dump($report, 4));
    }

    protected function executeGenerate($filename, OutputInterface $output)
    {
        $service = $this->container->get('dokudoki.migration.black2white');
        $cfg = \Symfony\Component\Yaml\Yaml::parse(file_get_contents($filename));
        $generatedClass = $service->generate($cfg['alias']);
        foreach ($generatedClass as $idx => $content) {
            file_put_contents(dirname($filename) . "/class$idx.php", $content);
        }
        $output->writeln(count($generatedClass) . " classes were generated.");
    }

}