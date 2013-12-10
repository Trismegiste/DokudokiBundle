<?php

namespace Trismegiste\DokudokiBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension as BaseExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Extension for the configuration of this bundle
 */
class Extension extends BaseExtension
{

    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $container->getDefinition('dokudoki.connector')->addArgument($config);
        $container->getDefinition('dokudoki.builder.whitemagic')->addArgument($config['alias']);
        $container->getDefinition('dokudoki.builder.hoodoo')->addArgument($config['alias']);
        $container->getDefinition('dokudoki.migration.black2white')->addArgument($config['alias']);

        if ($container->hasParameter('kernel.debug') && $container->getParameter('kernel.debug')) {
            $def = new Definition('Trismegiste\DokudokiBundle\Persistence\RepositoryDebug', [
                new Reference('dokudoki.repository.' . $config['stage']),
                new Reference('dokudoki.data_collector.db')
            ]);
            $container->setDefinition('dokudoki.repository', $def);
        } else {
            $container->setAlias('dokudoki.repository', 'dokudoki.repository.' . $config['stage']);
        }
    }

    public function getAlias()
    {
        return 'dokudoki';
    }

}