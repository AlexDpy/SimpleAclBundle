<?php

namespace AlexDpy\SimpleAclBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
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

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if (null !== $config['connection']) {
            $container->setAlias(
                'alex_dpy_simple_acl.connection',
                sprintf('doctrine.dbal.%s_connection', $config['connection'])
            );
        }

        $container
            ->getDefinition('alex_dpy_simple_acl.schema_listener')
            ->addTag('doctrine.event_listener', array(
                'connection' => $config['connection'],
                'event' => 'postGenerateSchema',
                'lazy' => true,
            ))
        ;

        if (isset($config['cache'])) {
            $aclCacheDefinition = new Definition($config['cache']['class']);
            $aclCacheDefinition->addMethodCall('setNamespace', [$config['cache']['namespace']]);
            $aclCacheDefinition->setPublic(false);

            $container->setDefinition('alex_dpy_simple_acl.cache', $aclCacheDefinition);

            $container->getDefinition('alex_dpy_simple_acl.permission_buffer')->addArgument(
                new Reference('alex_dpy_simple_acl.cache')
            );
        }

        $container->setParameter('alex_dpy_simple_acl.mask_builder_class', $config['mask_builder_class']);

        $container->setParameter(
            'alex_dpy_simple_acl.schema_options.permissions_table_name',
            $config['schema']['permissions_table_name']
        );
        $container->setParameter(
            'alex_dpy_simple_acl.schema_options.requester_column_length',
            $config['schema']['requester_column_length']
        );
        $container->setParameter(
            'alex_dpy_simple_acl.schema_options.resource_column_length',
            $config['schema']['resource_column_length']
        );

        $container->setParameter('alex_dpy_simple_acl.schema_options', $config['schema']);
    }
}
