<?php

namespace AlexDpy\SimpleAclBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AlexDpySimpleAclExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $container->setAlias(
            'alex_dpy_simple_acl.database_provider',
            new Alias($config['database_provider'])
        );

        if (isset($config['cache_provider'])) {
            $container->getDefinition('alex_dpy_simple_acl.permission_buffer')->addArgument(
                new Reference($config['cache_provider'])
            );
        }

        $container->setParameter('alex_dpy_simple_acl.mask_builder_class', $config['mask_builder_class']);

        $container->setParameter(
            'alex_dpy_simple_acl.schema_options.permissions_table_name',
            $config['schema']['permissions_table_name']
        );
    }
}
