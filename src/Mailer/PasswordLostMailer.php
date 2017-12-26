<?php

namespace App\Mailer;

use App\Entity\User;
use App\Logger\Log;

class PasswordLostMailer extends AbstractMailer
{
    public function execute(User $user): void
    {
        $body = $this->twig->render(
            'mailing/password-lost.html.twig',
            [
                'user' => $user
            ]
        );

        $message = $this->buildMessage(
            $user->getEmail(),
            Mail::SUBJECT_PASSWORD_LOST,
            $body
        );

        $this->mailer->send($message);

        $this->logger->info(sprintf('[%s] Mail sent', Log::SUBJECT_PASSWORD_LOST));
    }
}
