<?php

namespace Lumbendil\SharedRoutersBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class LumbendilSharedRoutersExtension extends Extension
{
    private $kernel;

    function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        foreach ($config['routers'] as $router => $routerConfig) {
            $this->buildRouterConfig($container, $router, $routerConfig);
        }
    }

    private function buildRouterConfig(ContainerBuilder $container, $name, $config)
    {
        $routerServiceName = sprintf('lumbendil_shared_routers.%s_router', $name);
        $routerAlias = sprintf('%s_router', $name);
        $requestContextServiceName = sprintf('lumbendil_shared_routers.%s_request_context', $name);
        $routerCacheWarmerServiceName = sprintf('lumbendil_shared_routers.%s_router.cache_warmer', $name);

        $router = new DefinitionDecorator('lumbendil_shared_routers.base_router');
        $requestContext = new DefinitionDecorator('lumbendil_shared_routers.base_request_context');
        $routerCacheWarmer = new DefinitionDecorator('router.cache_warmer');

        $router->replaceArgument(1, $this->kernel->locateResource($config['resource']));
        $router->replaceArgument(2, array(
                'cache_dir' => '%kernel.cache_dir%',
                'debug' => '%kernel.debug%',
                'strict_requirements' => '%kernel.debug%',
                'generator_class' => '%router.options.generator_class%',
                'generator_base_class' => '%router.options.generator_base_class%',
                'generator_dumper_class' => '%router.options.generator_dumper_class%',
                'generator_cache_class' => 'app%kernel.environment%' . $name . 'UrlGenerator',
            )
        );
        $router->replaceArgument(3, new Reference($requestContextServiceName));
        $routerCacheWarmer->replaceArgument(0, new Reference($routerServiceName));
        $routerCacheWarmer->addTag('kernel.cache_warmer');

        if (isset($config['configurator_service'])) {
            $requestContext->addMethodCall(
                'setConfigurator',
                array(
                    new Reference($config['configurator_service']),
                    $config['configurator_options']
                )
            );
        }

        $requestContext->addMethodCall('fromRequest', array(new Reference('request', ContainerInterface::IGNORE_ON_INVALID_REFERENCE, false)));

        $container->setDefinition($routerServiceName, $router);
        $container->setDefinition($requestContextServiceName, $requestContext);
        $container->setDefinition($routerCacheWarmerServiceName, $routerCacheWarmer);
        $container->setAlias($routerAlias, $routerServiceName);
    }
}
