<?php

declare(strict_types=1);

namespace Playtini\GrpcServerBundle\Worker;

use Playtini\GrpcServerBundle\Grpc\GrpcServiceProvider;
use Psr\Log\LoggerInterface;
use Spiral\GRPC\Server;
use Spiral\RoadRunner\Worker;


final class GrpcWorker implements GrpcWorkerInterface
{
    private LoggerInterface $logger;
    
    private Worker $worker;
    
    private GrpcServiceProvider $grpcServiceProvider;

    public function __construct(LoggerInterface $logger, Worker $worker, GrpcServiceProvider $grpcServiceProvider) {
        $this->logger = $logger;
        $this->worker = $worker;
        $this->grpcServiceProvider = $grpcServiceProvider;
    }

    public function start(): void
    {
        $server = new Server();
        
        foreach ($this->grpcServiceProvider->getRegisteredServices() as $interface => $service) {
            $this->logger->debug('Register GRPC service for '.$interface.', from '.\get_class($service));
            $server->registerService($interface, $service);
        }

        $server->serve($this->worker);
    }
}