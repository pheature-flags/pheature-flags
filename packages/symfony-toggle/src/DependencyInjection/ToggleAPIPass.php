<?php

declare(strict_types=1);

namespace Pheature\Community\Symfony\DependencyInjection;

use Nyholm\Psr7\Factory\Psr17Factory;
use Pheature\Community\Symfony\Toggle\Transformer\NyholmPsrToSymfonyResponseTransformer;
use Pheature\Community\Symfony\Toggle\Transformer\NyholmSymfonyToPsrRequestTransformer;
use Pheature\Community\Symfony\Toggle\Transformer\PsrToSymfonyResponseTransformer;
use Pheature\Community\Symfony\Toggle\Transformer\SymfonyToPsrRequestTransformer;
use Psr\Http\Message\ResponseFactoryInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class ToggleAPIPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        /** @var array<array<mixed>> $pheatureFlagsConfig */
        $pheatureFlagsConfig = $container->getExtensionConfig('pheature_flags');
        $mergedConfig = array_merge(...$pheatureFlagsConfig);

        if (false === $mergedConfig['api_enabled']) {
            return;
        }

        $this->addPsrServices($container);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config/toggle_api')
        );
        $loader->load('services/controller_services.yaml');
        $loader->load('services/handler_services.yaml');
    }

    private function addPsrServices(ContainerBuilder $container): void
    {
        $container->register(SymfonyToPsrRequestTransformer::class, NyholmSymfonyToPsrRequestTransformer::class)
            ->setAutowired(false)
            ->setLazy(true);
        $container->register(PsrToSymfonyResponseTransformer::class, NyholmPsrToSymfonyResponseTransformer::class)
            ->setAutowired(false)
            ->setLazy(true);
        $container->register(ResponseFactoryInterface::class, Psr17Factory::class)
            ->setAutowired(false)
            ->setLazy(true);
    }
}
