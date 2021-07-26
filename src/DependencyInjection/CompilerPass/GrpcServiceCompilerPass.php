<?php

declare(strict_types=1);

namespace Playtini\GrpcServerBundle\DependencyInjection\CompilerPass;

use Playtini\GrpcServerBundle\Grpc\GrpcServiceProvider;
use Spiral\GRPC\ServiceInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Reference;

class GrpcServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(GrpcServiceProvider::class)) {
            
            return;
        }

        $provider = $container->findDefinition(GrpcServiceProvider::class);
        $taggedServices = $container->findTaggedServiceIds('playtini.roadrunner.grpc_service');
        
        foreach ($taggedServices as $id => $tags) {
            $classInterfaces = class_implements($id);
            
            $serviceInterface = null;
            foreach ($classInterfaces as $interface) {
                if (is_subclass_of($interface, ServiceInterface::class)) {
                    $serviceInterface = $interface;
                }
            }
            
            if (!$serviceInterface) {
                throw new InvalidArgumentException($id.' should implement '.ServiceInterface::class);
            }

            $provider->addMethodCall('registerService', [$serviceInterface, new Reference($id)]);
        }
    }
}