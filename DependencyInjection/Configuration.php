<?php

namespace AlexDpy\SimpleAclBundle\DependencyInjection;

use AlexDpy\Acl\Database\Schema\AclSchema;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('alex_dpy_simple_acl');

        $rootNode
            ->children()
                ->scalarNode('database_provider')
                    ->isRequired()
                ->end()
                ->scalarNode('mask_builder_class')
                    ->defaultValue('AlexDpy\Acl\Mask\BasicMaskBuilder')
                    ->info('a class that implements AlexDpy\Acl\Mask\MaskBuilderInterface')
                ->end()
                ->scalarNode('cache_provider')
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('schema')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('permissions_table_name')
                            ->defaultValue(AclSchema::DEFAULT_PERMISSIONS_TABLE_NAME)
                        ->end()
                        ->scalarNode('requester_column_length')
                            ->defaultValue(AclSchema::DEFAULT_REQUESTER_COLUMN_LENGTH)
                        ->end()
                        ->scalarNode('resource_column_length')
                            ->defaultValue(AclSchema::DEFAULT_RESOURCE_COLUMN_LENGTH)
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
