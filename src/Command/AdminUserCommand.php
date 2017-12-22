<?php

namespace App\Command;

use App\Entity\User;
use App\Security\Role;
use App\Workflow\RegistrationDefinitionWorkflow;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AdminUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:admin:create')
            ->setDescription('Create user')
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addArgument('password', InputArgument::REQUIRED, 'Password (not encoded)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $user = $this->createUser($input);
        $errors = $this->getContainer()->get('validator')->validate($user);

        if (count($errors)) {
            $output->writeln('<error>User is not valid.</error>');
            $output->writeln((string) $errors);

            return;
        }

        $em->persist($user);
        $em->flush();

        $output->writeln('<info>User created.</info>');
    }

    private function createUser(InputInterface $input): User
    {
        $user = new User();
        $user->setUsername($input->getArgument('username'));
        $user->setRoles([Role::ADMIN]);
        $user->setPassword($input->getArgument('password'));
        $user->setFirstName($input->getArgument('username'));
        $user->setLastName($input->getArgument('username'));
        $user->setEmail(sprintf('%s@retrainrd.com', $input->getArgument('username')));
        $user->setRegistrationCode('');
        $user->setRegistrationState(RegistrationDefinitionWorkflow::PLACE_ACTIVATED);
        $user->setEnabled(true);

        $user->setPassword(
            $this->getContainer()->get('security.password_encoder')->encodePassword($user, $user->getPassword())
        );

        return $user;
    }
}
