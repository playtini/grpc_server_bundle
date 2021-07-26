<?php

declare(strict_types=1);

namespace Playtini\GrpcServerBundle\Worker;

interface GrpcWorkerInterface
{
    public function start(): void;
}