<?php

namespace Trismegiste\DokudokiBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension as BaseExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

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

        $container->setParameter('dokudoki.config.server', $config['server']);
        $container->setParameter('dokudoki.config.database', $config['database']);
        $container->setParameter('dokudoki.config.collection', $config['collection']);
        $container->getDefinition('dokudoki.stage.whitemagic')->addArgument($config['alias']);
        $container->getDefinition('dokudoki.stage.hoodoo')->addArgument($config['alias']);


//
//
//        // factory
//        $container->setDefinition('dokudoki.factory', new Definition(
//                        'Trismegiste\DokudokiBundle\Model\Factory',
//                        array($config['alias'])
//                )
//        );
//        // repository
//        $container->setDefinition('dokudoki.repository', new Definition(
//                        'Trismegiste\DokudokiBundle\Persistence\Repository',
//                        array(new Reference('dokudoki.collection'), new Reference('dokudoki.factory'))
//                )
//        );
//        // type mongobindata
//        $def = new Definition('Trismegiste\MongoSapinBundle\Form\MongoBinDataType');
//        $def->addTag('form.type');
//        $container->setDefinition('mongosapin_file', $def);
//        // magic form type
//        $def = new Definition('Trismegiste\MongoSapinBundle\Form\MagicFormType',
//                        array(new Reference('dokudoki.factory')));
//        $def->addTag('form.type');
//        $container->setDefinition('magic_form', $def);
    }

    public function getAlias()
    {
        return 'dokudoki';
    }

}