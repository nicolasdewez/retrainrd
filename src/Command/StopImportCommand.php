<?php

namespace App\Command;

use App\Client\SNCFGetStopClient;
use App\Manager\StopManager;
use App\Transformer\SNCFToEntityTransformer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StopImportCommand extends Command
{
    /** @var SNCFGetStopClient */
    private $client;

    /** @var ValidatorInterface */
    private $validator;

    /** @var StopManager */
    private $manager;

    /** @var SNCFToEntityTransformer */
    private $transformer;

    public function __construct(SNCFGetStopClient $client, ValidatorInterface $validator, StopManager $manager, SNCFToEntityTransformer $transformer)
    {
        parent::__construct();

        $this->client = $client;
        $this->validator = $validator;
        $this->manager = $manager;
        $this->transformer = $transformer;
    }

    public function configure()
    {
        $this
            ->setName('app:stop:import')
            ->setDescription('Import stops of SNCF')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Call api "get stop areas"</info>');
        $stopAreas = $this->client->getStops();

        $output->writeln(sprintf('> %d stops to processed', count($stopAreas)));

        $output->writeln('<info>Create or edit each entity</info>');

        $stops = [];
        foreach ($stopAreas as $key => $stopArea) {
            $stop = $this->manager->getStopByCode($stopArea->getId());
            $stop = $this->transformer->execute($stopArea, $stop);

            $errors = $this->validator->validate($stop);
            $nbErrors = count($errors);
            if ($nbErrors) {
                $output->writeln(sprintf('> There are %s errors for stop %s.', $nbErrors, $stop->getCode()));
                continue;
            }

            $stops[] = $stop;
        }

        $output->writeln('<info>Save in database</info>');
        $this->manager->saveStops($stops);

        $output->writeln('<info>Process finished</info>');
    }
}
