<?php

namespace Lumbendil\SharedRoutersBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('lumbendil_shared_routers');

        $rootNode
            ->children()
                ->arrayNode('routers')
                    ->defaultValue(array())
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('resource')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('configurator_service')->cannotBeEmpty()->end()
                            ->arrayNode('configurator_options')
                                ->defaultValue(array())
                                ->prototype('variable')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
