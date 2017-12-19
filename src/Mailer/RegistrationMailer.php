<?php

namespace App\Mailer;

use App\Entity\User;
use App\Logger\Log;

class RegistrationMailer extends AbstractMailer
{
    public function execute(User $user)
    {
        $body = $this->twig->render(
            'mailing/registration.html.twig',
            [
                'user' => $user,
            ]
        );

        $message = $this->buildMessage(
            $user->getEmail(),
            Mail::SUBJECT_REGISTRATION,
            $body
        );

        $this->mailer->send($message);

        $this->logger->info(sprintf('[%s] Mail sent', Log::SUBJECT_REGISTRATION));
    }
}
