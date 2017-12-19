<?php

namespace App\Security;

use App\Entity\User;
use App\Logger\Log;
use App\Mailer\PasswordLostMailer;
use App\Workflow\RegistrationWorkflow;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserPasswordLost
{
    /** @var RegistrationWorkflow */
    private $workflow;

    /** @var EntityManagerInterface */
    private $manager;

    /** @var UserPasswordEncoderInterface */
    private $encoder;

    /** @var GenerateRegistrationCode */
    private $generateRegistrationCode;

    /** @var PasswordLostMailer */
    private $mailer;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        RegistrationWorkflow $workflow,
        EntityManagerInterface $manager,
        UserPasswordEncoderInterface $encoder,
        GenerateRegistrationCode $generateRegistrationCode,
        PasswordLostMailer $mailer,
        LoggerInterface $logger
    ) {
        $this->workflow = $workflow;
        $this->manager = $manager;
        $this->encoder = $encoder;
        $this->generateRegistrationCode = $generateRegistrationCode;
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public function execute(User $user)
    {
        if (!$this->workflow->canApplyPasswordLost($user)) {
            $this->logger->error(sprintf(
                'Process password lost can not be apply for user §s because workflow not support this.',
                $user->getUsername()
            ));

            return;
        }

        $user->setRegistrationCode($this->generateRegistrationCode->execute($user->getUsername()));
        $user->setPassword('');
        $user->setEnabled(false);

        $this->workflow->applyPasswordLost($user);

        $this->manager->flush();

        $this->logger->info(sprintf('[%s] Registration code generated', Log::SUBJECT_PASSWORD_LOST));

        $this->mailer->execute($user);
    }
}
