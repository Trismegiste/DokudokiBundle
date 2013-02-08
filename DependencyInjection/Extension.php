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

        // for each magic stage
        foreach (array('blackmagic', 'invocation', 'whitemagic', 'hoodoo') as $stage) {
            // I build the mediator (mapper)
            $container->setDefinition('dokudoki.mapper.' . $stage, new Definition(
                                    'Trismegiste\DokudokiBundle\Transform\Mediator\TypeRegistry',
                                    array(new Reference('dokudoki.builder.' . $stage))
                            )
                    )
                    ->setFactoryService('dokudoki.director')
                    ->setFactoryMethod('create')
                    ->setPublic(false);
            // then I build the Transformer which delegates to the builder Mediator
            $container->setDefinition('dokudoki.transform.' . $stage, new Definition(
                                    'Trismegiste\DokudokiBundle\Transform\Transformer',
                                    array(new Reference('dokudoki.mapper.' . $stage))
                            )
                    )
                    ->setPublic(false);
            // and then I build the repository thereafter
            $container->setDefinition('dokudoki.repository.' . $stage, new Definition(
                            'Trismegiste\DokudokiBundle\Persistence\Repository',
                            array(
                                new Reference('dokudoki.collection'),
                                new Reference('dokudoki.transform.' . $stage)
                            )
                    )
            );
        }

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