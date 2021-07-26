<?php

declare(strict_types=1);

namespace Playtini\GrpcServerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class PlaytiniGrpcServerExtension extends Extension
{
    const MONOLOG_CHANNEL = 'grpc_server';
    
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../config'));
        $loader->load('service.yaml');
    }
}
