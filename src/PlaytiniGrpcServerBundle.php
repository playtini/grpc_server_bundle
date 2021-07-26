<?php

declare(strict_types=1);

namespace Playtini\GrpcServerBundle;

use Playtini\GrpcServerBundle\DependencyInjection\CompilerPass\GrpcServiceCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class PlaytiniGrpcServerBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        
        $container->addCompilerPass(new GrpcServiceCompilerPass());
    }
}
