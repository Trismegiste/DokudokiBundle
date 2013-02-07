<?php

namespace Trismegiste\DokudokiBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension as BaseExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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
        $container->
        // connector
        $container->setDefinition('dokudoki.connector', new Definition(
                        'Trismegiste\DokudokiBundle\Persistence\Connector',
                        array($config)
                )
        );
        // collection
        $container->setDefinition('dokudoki.collection', new Definition('MongoCollection'))
                ->setFactoryService('dokudoki.connector')
                ->setFactoryMethod('getCollection');
        // factory
        $container->setDefinition('dokudoki.factory', new Definition(
                        'Trismegiste\DokudokiBundle\Model\Factory',
                        array($config['alias'])
                )
        );
        // repository
        $container->setDefinition('dokudoki.repository', new Definition(
                        'Trismegiste\DokudokiBundle\Persistence\Repository',
                        array(new Reference('dokudoki.collection'), new Reference('dokudoki.factory'))
                )
        );
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