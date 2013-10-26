<?php

namespace Aqarmap\Bundle\MultiRegionBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('multi_region');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $rootNode
            ->children()
                # All available locales
                ->arrayNode('available_locales')
                    ->beforeNormalization()
                        ->ifString()
                        ->then(function($v) { return preg_split('/\s*,\s*/', $v); })
                    ->end()
                    ->requiresAtLeastOneElement()
                    ->prototype('scalar')->end()
                ->end()

                // List of supported regions
                ->arrayNode('regions')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('enabled_locales')
                                ->prototype('scalar')->end()
                            ->end()
                            ->scalarNode('default_locale')->isRequired()->end()
                            ->scalarNode('url_pattern')->defaultValue('/{_region}/{_locale}{_pattern}')->end()
                        ->end()
                    ->end()
                ->end()

                // The default region
                ->scalarNode('default_region')->isRequired()
            ->end()
        ;

        return $treeBuilder;
    }
}
