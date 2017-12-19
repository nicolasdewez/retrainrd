<?php

namespace App\Mailer;

use App\Entity\User;
use App\Logger\Log;

class DisableAccountMailer extends AbstractMailer
{
    public function execute(User $user)
    {
        $body = $this->twig->render(
            'mailing/disable-account.html.twig',
            [
                'user' => $user,
            ]
        );

        $message = $this->buildMessage(
            $user->getEmail(),
            Mail::SUBJECT_DISABLE_ACCOUNT,
            $body
        );

        $this->mailer->send($message);

        $this->logger->info(sprintf('[%s] Mail sent', Log::SUBJECT_DISABLE_ACCOUNT));
    }
}
