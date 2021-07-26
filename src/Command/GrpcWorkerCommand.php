<?php

declare(strict_types=1);

namespace Playtini\GrpcServerBundle\Command;

use Playtini\GrpcServerBundle\Worker\GrpcWorkerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'roadrunner:grpc-worker',
    description: 'Run the roadrunner grpc worker',
    hidden: true
)]
final class GrpcWorkerCommand extends Command
{
    protected static $defaultName = 'roadrunner:grpc-worker';

    private GrpcWorkerInterface $worker;

    public function __construct(GrpcWorkerInterface $worker)
    {
        parent::__construct();

        $this->worker = $worker;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->worker->start();

        return Command::SUCCESS;
    }
}