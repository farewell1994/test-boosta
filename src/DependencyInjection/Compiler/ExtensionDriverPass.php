<?php

namespace App\DependencyInjection\Compiler;

use App\Service\ExtensionChain;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\{
    ContainerBuilder, Reference
};

/**
 * Class ExtensionDriverPass
 * @package App\DependencyInjection\Compiler
 */
class ExtensionDriverPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(ExtensionChain::class)) {
            return;
        }

        $definition = $container->findDefinition(ExtensionChain::class);
        $taggedServices = $container->findTaggedServiceIds('app.extension_driver');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall('addDriver', [
                    new Reference($id), $attributes['alias']
                ]);
            }
        }
    }
}
