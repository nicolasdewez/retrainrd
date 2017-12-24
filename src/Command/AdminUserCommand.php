<?php

namespace App\Command;

use App\Entity\User;
use App\Security\Role;
use App\Workflow\RegistrationDefinitionWorkflow;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AdminUserCommand extends Command
{
    /** @var EntityManagerInterface */
    private $manager;

    /** @var UserPasswordEncoderInterface */
    private $userPasswordEncoder;

    /** @var ValidatorInterface */
    private $validator;

    public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $userPasswordEncoder, ValidatorInterface $validator)
    {
        parent::__construct();

        $this->validator = $validator;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->manager = $manager;
    }

    protected function configure()
    {
        $this
            ->setName('app:admin:create')
            ->setDescription('Create user')
            ->addArgument('email', InputArgument::REQUIRED, 'Email')
            ->addArgument('password', InputArgument::REQUIRED, 'Password (not encoded)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = $this->createUser($input);
        $errors = $this->validator->validate($user);

        if (count($errors)) {
            $output->writeln('<error>User is not valid.</error>');
            $output->writeln((string) $errors);

            return;
        }

        $this->manager->persist($user);
        $this->manager->flush();

        $output->writeln('<info>User created.</info>');
    }

    private function createUser(InputInterface $input): User
    {
        $user = new User();
        $user->setEmail($input->getArgument('email'));
        $user->setRoles([Role::ADMIN]);
        $user->setPassword($input->getArgument('password'));
        $user->setFirstName('');
        $user->setLastName('');
        $user->setRegistrationCode('');
        $user->setRegistrationState(RegistrationDefinitionWorkflow::PLACE_ACTIVATED);
        $user->setEnabled(true);

        $user->setPassword(
            $this->userPasswordEncoder->encodePassword($user, $user->getPassword())
        );

        return $user;
    }
}
