<?php

namespace Trismegiste\DokudokiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * This class validates the configuration
 */
class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('dokudoki');

        $rootNode
                ->children()
                    ->scalarNode('stage')
                        ->isRequired()
                        ->cannotBeEmpty()
                        ->info('The magic stage used by this application')
                        ->example('blackmagic')
                    ->end()
                    ->scalarNode('server')
                        ->isRequired()
                        ->cannotBeEmpty()
                        ->info('The mongoDB server for persistence of Dokudoki')
                        ->example('db.domain.tld:27017')
                    ->end()
                    ->scalarNode('database')
                        ->isRequired()
                        ->cannotBeEmpty()
                        ->info('The database name for persistence of Dokudoki')
                        ->example('production')
                    ->end()
                    ->scalarNode('collection')
                        ->isRequired()
                        ->cannotBeEmpty()
                        ->info('The collection for persistence of documents')
                        ->example('myapp')
                    ->end()
                    ->arrayNode('alias')
                        ->useAttributeAsKey('key')
                        ->prototype('scalar')
                            ->cannotBeEmpty()
                            ->validate()
                                ->ifTrue(function($node) { return !is_null($node);} )
                                ->then(function($node) {
                                        if (!class_exists($node)) {
                                            throw new InvalidConfigurationException("FQCN $node does not exist");
                                        }
                                        return $node;
                                    })
                            ->end()
                        ->end()
                    ->end()
                ->end();

        return $treeBuilder;
    }

}