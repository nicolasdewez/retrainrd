<?php

namespace App\Command;

use OldSound\RabbitMqBundle\RabbitMq\Producer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TerminateConsumerCommand extends Command
{
    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->container = $container;
    }

    public function configure(): void
    {
        $this
            ->setName('app:consumer:terminate')
            ->addArgument('consumer', InputArgument::REQUIRED, 'Consumer name')
            ->setHelp('Terminate all consumers');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $consumer = $input->getArgument('consumer');
        $producerName = sprintf('old_sound_rabbit_mq.%s_producer', $consumer);
        if (!$this->container->has($producerName)) {
            $output->writeln(sprintf('<error>Consumer %s not found or its producer</error>', $consumer));

            return;
        }

        $pids = $this->getConsumerPids($consumer);
        if (0 === count($pids)) {
            $output->writeln('<comment>No consumer process found</comment>');

            return;
        }

        foreach ($pids as $pid) {
            if (posix_kill($pid, SIGTERM)) {
                $output->writeln(sprintf('Signal SIGTERM sent to PID %d', $pid));
            } else {
                $output->writeln(sprintf('<comment>Was unable to send SIGTERM to %d', $pid));
            }
        }

        /** @var Producer $producer */
        $producer = $this->container->get($producerName);
        for ($i = 0; $i < count($pids); ++$i) {
            $producer->publish(serialize([
                '_ping' => true,
            ]));
        }

        // Sleep 1 second and check for processes
        sleep(1);
        $stillAliveConsumers = $this->getConsumerPids($consumer);
        if (0 !== count($stillAliveConsumers)) {
            $output->writeln(sprintf(
                '<error>%d consumer processes seems to be found</error>',
                count($stillAliveConsumers)
            ));

            return;
        }

        $output->writeln(sprintf(
            '<info>%d consumer were terminated</info>',
            count($pids)
        ));
    }

    private function getConsumerPids(string $consumer): array
    {
        $pids = [];

        exec(sprintf('ps ax | grep "rabbitmq:consumer %s"', $consumer), $lines);
        foreach ($lines as $line) {
            if (false !== strpos($line, 'bin/console') && preg_match('#^([ ]*)([0-9]+)#', $line, $matches)) {
                $pids[] = $matches[2];
            }
        }

        return $pids;
    }
}
