<?php

namespace App\Security;

use App\Entity\User;
use App\Logger\Log;
use App\Mailer\RegistrationMailer;
use App\Workflow\RegistrationWorkflow;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class UserRegistration
{
    /** @var RegistrationWorkflow */
    private $workflow;

    /** @var EntityManagerInterface */
    private $manager;

    /** @var GenerateRegistrationCode */
    private $generateRegistrationCode;

    /** @var RegistrationMailer */
    private $mailer;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        RegistrationWorkflow $workflow,
        EntityManagerInterface $manager,
        GenerateRegistrationCode $generateRegistrationCode,
        RegistrationMailer $mailer,
        LoggerInterface $logger
    ) {
        $this->workflow = $workflow;
        $this->manager = $manager;
        $this->generateRegistrationCode = $generateRegistrationCode;
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public function execute(User $user)
    {
        if (!$this->workflow->canApplyRegistration($user)) {
            $this->logger->error(sprintf(
                'User %s can not be registered because workflow not support this.',
                $user->getUsername()
            ));

            return;
        }

        $user->setRegistrationCode($this->generateRegistrationCode->execute($user->getUsername()));

        $this->workflow->applyRegistration($user);

        $this->manager->flush();

        $this->logger->info(sprintf('[%s] Registration code generated', Log::SUBJECT_REGISTRATION));

        $this->mailer->execute($user);
    }
}
