<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class LaunchConsumerCommand extends Command
{
    const CONSUMER_LAUNCH_COMMAND = 'rabbitmq:consumer';

    /** @var string */
    private $projectDir;

    public function __construct(string $projectDir)
    {
        parent::__construct();
        $this->projectDir = $projectDir;
    }

    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this
            ->setName('app:consumer:launch')
            ->addArgument('consumer', InputArgument::REQUIRED, 'Consumer name')
            ->addArgument('number', InputArgument::REQUIRED, 'Consumer number')
            ->setHelp('Launch x consumers');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $number = $input->getArgument('number');
        $consumer = $input->getArgument('consumer');

        $binPath = realpath(sprintf('%s/bin/console', $this->projectDir));

        for ($i = 0; $i < $number; ++$i) {
            $process = new Process(sprintf('%s %s %s', $binPath, self::CONSUMER_LAUNCH_COMMAND, $consumer));
            $process->start();
        }

        $output->writeln(sprintf('<info>%d %s consumer(s) launched</info>', $number, $consumer));
    }
}
